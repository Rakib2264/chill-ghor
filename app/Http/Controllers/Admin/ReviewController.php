<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $q = Review::with(['product', 'user'])->latest();
        if ($request->filled('product_id')) $q->where('product_id', $request->product_id);
        if ($request->filled('status')) $q->where('is_approved', $request->status === 'approved');
        $reviews = $q->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get(['id','name']);
        return view('admin.reviews.index', compact('reviews','products'));
    }
    public function toggle(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        return back()->with('toast', $review->is_approved ? '✅ রিভিউ অনুমোদিত' : '⏸️ রিভিউ আনঅ্যাপ্রুভ');
    }
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('toast', 'রিভিউ মুছে ফেলা হয়েছে');
    }
}
