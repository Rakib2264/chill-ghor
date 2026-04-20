<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => auth()->id()],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null, 'is_approved' => true],
        );

        return back()->with('toast', '⭐ আপনার রিভিউ জমা হয়েছে — ধন্যবাদ!');
    }

    public function destroy(Review $review)
    {
        abort_unless(auth()->id() === $review->user_id || (auth()->user()->is_admin ?? false), 403);
        $review->delete();
        return back()->with('toast', '🗑️ রিভিউ মুছে ফেলা হয়েছে');
    }
}
