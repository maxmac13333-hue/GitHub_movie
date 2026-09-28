<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Theater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SeatController extends Controller
{
    private const SEAT_TYPES = [
        'Deluxe - 180 บาท',
        'Premium - 240 บาท',
        'VIP - 350 บาท',
    ];

    public function store(Request $request, Theater $theater): RedirectResponse
    {
        $validated = $request->validate([
            'pos_x' => ['required', 'integer', 'between:1,8'],
            'pos_y' => ['required', 'integer', 'between:1,9'],
        ]);

        $created = DB::transaction(function () use ($theater, $validated) {
            $lockedTheater = Theater::whereKey($theater->getKey())->lockForUpdate()->firstOrFail();
            $occupied = $lockedTheater->seats()
                ->where('pos_x', $validated['pos_x'])
                ->where('pos_y', $validated['pos_y'])
                ->exists();

            if ($occupied) {
                return false;
            }

            $row = chr(64 + $validated['pos_y']);
            $seat = new Seat();
            $seat->seat_row = $row;
            $seat->seat_number = $validated['pos_x'];
            $seat->Seat_No = $row . $validated['pos_x'];
            $seat->Seat_Type = self::SEAT_TYPES[0];
            $seat->pos_x = $validated['pos_x'];
            $seat->pos_y = $validated['pos_y'];
            $lockedTheater->seats()->save($seat);

            return true;
        });

        if (!$created) {
            return back()->withErrors(['seat' => __('ตำแหน่งนี้มีที่นั่งอยู่แล้ว')]);
        }

        return redirect()->route('admin.theaters.edit', $theater)->with('status', __('เพิ่มที่นั่งแล้ว'));
    }

    public function update(Request $request, Seat $seat): RedirectResponse
    {
        $validated = $request->validate([
            'Seat_Type' => ['required', Rule::in(self::SEAT_TYPES)],
        ]);

        $seat->Seat_Type = $validated['Seat_Type'];
        $seat->save();

        return redirect()->route('admin.theaters.edit', $seat->Theater_ID)->with('status', __('บันทึกข้อมูลที่นั่งแล้ว'));
    }

    public function destroy(Seat $seat): RedirectResponse
    {
        $theaterId = $seat->Theater_ID;
        $deleted = DB::transaction(function () use ($seat) {
            $lockedSeat = Seat::whereKey($seat->getKey())->lockForUpdate()->firstOrFail();

            if (DB::table('ticket')->where('Seat_ID', $lockedSeat->Seat_ID)->exists()) {
                return false;
            }

            $lockedSeat->delete();

            return true;
        });

        if (!$deleted) {
            return redirect()->route('admin.theaters.edit', $theaterId)
                ->withErrors(['seat' => __('ลบที่นั่งที่มีรายการจองไม่ได้')]);
        }

        return redirect()->route('admin.theaters.edit', $theaterId)->with('status', __('ลบที่นั่งแล้ว'));
    }
}