<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $users = User::query()
            ->when($q !== '', fn($qq) => $qq->where(fn($x) =>
                $x->where('name', 'like', "%$q%")
                  ->orWhere('email', 'like', "%$q%")
                  ->orWhere('phone', 'like', "%$q%")
            ))
            ->withCount('orders')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('toast', '⚠️ নিজেকে পরিবর্তন করা যাবে না');
        }
        $user->update(['is_admin' => !$user->is_admin]);
        return back()->with('toast', '✅ ভূমিকা আপডেট হয়েছে');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('toast', '⚠️ নিজেকে মুছে ফেলা যাবে না');
        }
        $user->delete();
        return back()->with('toast', 'ব্যবহারকারী মুছে ফেলা হয়েছে');
    }
}
