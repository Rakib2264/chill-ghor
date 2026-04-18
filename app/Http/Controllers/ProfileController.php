<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'delivered')->sum('total');
        
        return view('pages.profile.index', compact('user', 'recentOrders', 'totalOrders', 'totalSpent'));
    }

    public function orders()
    {
        $orders = Auth::user()->orders()
            ->with('items')
            ->latest()
            ->paginate(15);
        
        return view('pages.profile.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'আপনার এই অর্ডার দেখার অনুমতি নেই।');
        }
        
        $order->load('items.product');
        return view('pages.profile.order-detail', compact('order'));
    }

    public function printInvoice(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'আপনার এই অর্ডার দেখার অনুমতি নেই।');
        }
        
        $order->load('items.product');
        return view('pages.profile.print-invoice', compact('order'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'avatar'   => 'nullable|image|max:2048',
        ], [
            'avatar.max' => 'ছবির সাইজ ২ মেগাবাইটের বেশি হতে পারবে না',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('toast', '✅ আপনার প্রোফাইল আপডেট হয়েছে');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string|current_password',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',
        ]);

        Auth::user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('toast', '🔐 পাসওয়ার্ড পরিবর্তন হয়েছে');
    }
}