<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    protected static function get(): array
    {
        return session('cart', []);
    }

    protected static function put(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public static function add(int $productId, int $qty = 1): void
    {
        $cart = self::get();
        $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
        self::put($cart);
    }

    public static function update(int $productId, int $qty): void
    {
        $cart = self::get();
        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $qty;
        }
        self::put($cart);
    }

    public static function remove(int $productId): void
    {
        $cart = self::get();
        unset($cart[$productId]);
        self::put($cart);
    }

    public static function clear(): void
    {
        session()->forget('cart');
    }

    /** Returns Collection of ['product' => Product, 'qty' => int] */
    public static function items(): Collection
    {
        $cart = self::get();
        if (empty($cart)) return collect();

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)->map(function ($qty, $id) use ($products) {
            if (!isset($products[$id])) return null;
            return ['product' => $products[$id], 'qty' => $qty];
        })->filter()->values();
    }

    public static function count(): int
    {
        return array_sum(self::get());
    }

    public static function subtotal(): int
    {
        return self::items()->sum(fn($i) => $i['product']->price * $i['qty']);
    }
}
