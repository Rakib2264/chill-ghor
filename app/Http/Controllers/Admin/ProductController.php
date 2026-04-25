<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products  = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();
        $trashCount = Product::onlyTrashed()->count();

        return view('admin.products.index', compact('products', 'categories', 'trashCount'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.form', [
            'product'    => new Product(['active' => true]),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['image'] = $this->handleImage($request) ?? 'images/food/food-biryani.jpg';
        $data['home_order'] = $data['home_order'] ?? 0;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('toast', '✅ পণ্য যোগ হয়েছে');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image_file')) {
            $newImage = $this->handleImage($request);
            if ($newImage) {
                $this->deleteOldImage($product->image);
                $data['image'] = $newImage;
            }
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('toast', '✅ পণ্য আপডেট হয়েছে');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('toast', '🗑️ পণ্য ট্র্যাশে পাঠানো হয়েছে');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->paginate(15);
        return view('admin.products.trash', compact('products'));
    }

    public function restore($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('toast', '♻️ পণ্য রিস্টোর হয়েছে');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->deleteOldImage($product->image);
        $product->forceDelete();
        return back()->with('toast', '🔥 পণ্য চিরতরে মুছে ফেলা হয়েছে');
    }

    public function homeManager()
    {
        $homeProducts = Product::where('show_on_home', true)
            ->orderBy('home_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.products.home-manager', compact('homeProducts'));
    }

    public function updateHomeOrder(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->products as $item) {
            Product::where('id', $item['id'])->update(['home_order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleHome(Request $request, Product $product)
    {
        $request->validate([
            'show_on_home' => 'required|boolean'
        ]);

        $product->update(['show_on_home' => $request->show_on_home]);

        return response()->json([
            'success' => true,
            'show_on_home' => $product->show_on_home
        ]);
    }

    public function bulkHomeUpdate(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:add,remove'
        ]);

        if ($request->action === 'add') {
            $maxOrder = Product::max('home_order') ?? 0;

            // FIXED: Changed comma to => in the foreach loop
            foreach ($request->product_ids as $index => $productId) {
                Product::where('id', $productId)->update([
                    'show_on_home' => true,
                    'home_order' => $maxOrder + $index + 1
                ]);
            }
            $message = count($request->product_ids) . ' টি প্রোডাক্ট হোম পেইজে যোগ করা হয়েছে';
        } else {
            Product::whereIn('id', $request->product_ids)->update([
                'show_on_home' => false,
                'home_order' => 0
            ]);
            $message = count($request->product_ids) . ' টি প্রোডাক্ট হোম পেইজ থেকে সরানো হয়েছে';
        }

        return back()->with('toast', '✅ ' . $message);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'             => 'required|string|max:120',
            'category_id'      => 'required|exists:categories,id',
            'price'            => 'required|integer|min:0',
            'old_price'        => 'nullable|integer|min:0',
            'description'      => 'required|string|max:300',
            'long_description' => 'nullable|string|max:2000',
            'popular'          => 'nullable|boolean',
            'spicy'            => 'nullable|boolean',
            'active'           => 'nullable|boolean',
            'image_file'       => 'nullable|image|max:2048',
            'show_on_home'     => 'nullable|boolean',
            'home_order'       => 'nullable|integer|min:0',
        ]) + [
            'popular'      => $request->boolean('popular'),
            'spicy'        => $request->boolean('spicy'),
            'active'       => $request->boolean('active'),
            'show_on_home' => $request->boolean('show_on_home'),
            'home_order'   => $request->integer('home_order', 0),
        ];
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function handleImage(Request $request): ?string
    {
        if (!$request->hasFile('image_file')) return null;
        $path = $request->file('image_file')->store('products', 'public');
        return 'storage/' . $path;
    }

    private function deleteOldImage(?string $image): void
    {
        if ($image && str_starts_with($image, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $image));
        }
    }

    public function toggleStatus(Request $request, $productId)
    {
        try {
            // Find the product
            $product = Product::find($productId);

            if (!$product) {
                return back()->with('error', 'পণ্যটি পাওয়া যায়নি');
            }

            // Validate the request
            $request->validate([
                'active' => 'required|boolean'
            ]);

            // Update the product status
            $product->update(['active' => $request->active]);

            // Return redirect with success message
            return back()->with('toast', $product->active ? '✅ পণ্য সক্রিয় করা হয়েছে' : '⛔ পণ্য নিষ্ক্রিয় করা হয়েছে');
        } catch (\Exception $e) {
            return back()->with('error', 'স্ট্যাটাস পরিবর্তন করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}
