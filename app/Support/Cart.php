<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    // ─── Read ──────────────────────────────────────────────────────────────────

    public static function all(): array
    {
        return session('cart', []);
    }

    public static function items(): Collection
    {
        $cart = static::all();
        if (empty($cart)) return collect();

        $productIds = array_keys($cart);
        $products   = Product::with('category')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($row, $id) use ($products) {
            $product = $products->get($id);
            if (!$product) return null;
            return [
                'product' => $product,
                'qty'     => $row['qty'],
            ];
        })->filter()->values();
    }

    public static function count(): int
    {
        return array_sum(array_column(static::all(), 'qty'));
    }

    public static function subtotal(): int
    {
        return static::items()->sum(fn($i) => $i['product']->price * $i['qty']);
    }

    /**
     * কার্টে একটি product-এর বর্তমান qty
     */
    public static function getQty(int $productId): int
    {
        $cart = static::all();
        return (int) ($cart[$productId]['qty'] ?? 0);
    }

    // ─── Write ─────────────────────────────────────────────────────────────────

    public static function add(Product $product, int $qty = 1): void
    {
        $cart = static::all();
        $id   = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = ['qty' => $qty];
        }

        session(['cart' => $cart]);
    }

    public static function update(int $productId, int $qty): void
    {
        $cart = static::all();

        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['qty'] = $qty;
        }

        session(['cart' => $cart]);
    }

    public static function remove(int $productId): void
    {
        $cart = static::all();
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }

    public static function clear(): void
    {
        session()->forget('cart');
    }
}