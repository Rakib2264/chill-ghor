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
        ]) + [
            'popular' => $request->boolean('popular'),
            'spicy'   => $request->boolean('spicy'),
            'active'  => $request->boolean('active'),
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
}
