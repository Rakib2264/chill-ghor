<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()
            ->addresses()
            ->with('deliveryZone')
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        $deliveryZones = DeliveryZone::where('is_active', true)->get();

        return view('pages.profile.addresses', compact('addresses', 'deliveryZones'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data) {
            if (!empty($data['is_default'])) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }
            // প্রথম ঠিকানা স্বয়ংক্রিয়ভাবে ডিফল্ট হবে
            if (Auth::user()->addresses()->count() === 0) {
                $data['is_default'] = true;
            }
            Auth::user()->addresses()->create($data);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'ok'        => true,
                'addresses' => Auth::user()
                    ->addresses()
                    ->with('deliveryZone')
                    ->orderByDesc('is_default')
                    ->latest()
                    ->get(),
            ]);
        }

        return back()->with('toast', '✅ ঠিকানা যোগ হয়েছে');
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeOwn($address);
        $data = $this->validateData($request);

        DB::transaction(function () use ($address, $data) {
            if (!empty($data['is_default'])) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }
            $address->update($data);
        });

        return back()->with('toast', '✅ ঠিকানা আপডেট হয়েছে');
    }

    public function destroy(Address $address)
    {
        $this->authorizeOwn($address);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Auth::user()->addresses()->latest()->first();
            if ($next) $next->update(['is_default' => true]);
        }

        return back()->with('toast', 'ঠিকানা মুছে ফেলা হয়েছে');
    }

    public function setDefault(Address $address)
    {
        $this->authorizeOwn($address);

        DB::transaction(function () use ($address) {
            Auth::user()->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return back()->with('toast', '✅ ডিফল্ট ঠিকানা সেট হয়েছে');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    protected function validateData(Request $r): array
    {
        return $r->validate([
            'label'            => 'required|string|max:40',
            'recipient_name'   => 'required|string|max:120',
            'phone'            => 'required|string|max:30',
            'area'             => 'nullable|string|max:120',
            'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
            'address_line'     => 'required|string|max:500',
            'is_default'       => 'nullable|boolean',
        ]);
    }

    protected function authorizeOwn(Address $a): void
    {
        abort_unless($a->user_id === Auth::id(), 403);
    }
}