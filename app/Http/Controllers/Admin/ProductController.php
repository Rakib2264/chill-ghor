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

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();
        $trashCount = Product::onlyTrashed()->count();

        return view('admin.products.index', compact('products', 'categories', 'trashCount'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.form', [
            'product'    => new Product(['active' => true, 'stock' => -1]),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data          = $this->validateData($request);
        $data['slug']  = $this->uniqueSlug($data['name']);
        $data['image'] = $this->handleImage($request) ?? 'images/food/food-biryani.jpg';

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('toast', '✅ পণ্য যোগ হয়েছে');
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

        return redirect()->route('admin.products.index')
            ->with('toast', '✅ পণ্য আপডেট হয়েছে');
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
            ->orderBy('home_order')
            ->orderBy('id')
            ->get();

        return view('admin.products.home-manager', compact('homeProducts'));
    }

    public function updateHomeOrder(Request $request)
    {
        $request->validate([
            'products'          => 'required|array',
            'products.*.id'     => 'required|exists:products,id',
            'products.*.order'  => 'required|integer|min:0',
        ]);

        foreach ($request->products as $item) {
            Product::where('id', $item['id'])->update(['home_order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleStatus(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $request->validate(['active' => 'required|boolean']);
        $product->update(['active' => $request->active]);

        return back()->with('toast', $product->active
            ? '✅ পণ্য সক্রিয় করা হয়েছে'
            : '⛔ পণ্য নিষ্ক্রিয় করা হয়েছে');
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
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
            'stock'            => 'nullable|integer|min:-1',  // ✅ stock
        ]);

        // Boolean defaults
        $validated['popular']      = $request->boolean('popular');
        $validated['spicy']        = $request->boolean('spicy');
        $validated['active']       = $request->boolean('active');
        $validated['show_on_home'] = $request->boolean('show_on_home');
        $validated['home_order']   = $request->integer('home_order', 0);
        $validated['stock']        = isset($validated['stock']) ? (int)$validated['stock'] : -1;

        return $validated;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $i    = 1;
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
}
