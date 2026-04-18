<?php

namespace App\Support;

class Wishlist
{
    public static function ids(): array
    {
        return session('wishlist', []);
    }

    public static function has(int $productId): bool
    {
        return in_array($productId, self::ids());
    }

    public static function toggle(int $productId): bool
    {
        $list = self::ids();
        if (in_array($productId, $list)) {
            session(['wishlist' => array_values(array_diff($list, [$productId]))]);
            return false;
        }
        $list[] = $productId;
        session(['wishlist' => $list]);
        return true;
    }

    public static function remove(int $productId): void
    {
        session(['wishlist' => array_values(array_diff(self::ids(), [$productId]))]);
    }

    public static function count(): int
    {
        return count(self::ids());
    }
}
