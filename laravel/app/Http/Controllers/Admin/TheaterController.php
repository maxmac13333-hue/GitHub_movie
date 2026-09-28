<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TheaterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'Theater_Name' => ['required', 'string', 'max:150'],
            'Theater_Location' => ['required', 'string', 'max:200'],
        ]);

        $theater = new Theater();
        $theater->Theater_Name = $validated['Theater_Name'];
        $theater->Theater_Location = $validated['Theater_Location'];
        $theater->save();

        return redirect()->route('admin.theaters')->with('status', __('เพิ่มโรงภาพยนตร์แล้ว'));
    }

    public function update(Request $request, int $theater): RedirectResponse
    {
        $validated = $request->validate([
            'Theater_Name' => ['required', 'string', 'max:150'],
            'Theater_Location' => ['required', 'string', 'max:200'],
        ]);

        $record = Theater::findOrFail($theater);
        $record->Theater_Name = $validated['Theater_Name'];
        $record->Theater_Location = $validated['Theater_Location'];
        $record->save();

        return redirect()->route('admin.theaters')->with('status', __('บันทึกการแก้ไขโรงภาพยนตร์แล้ว'));
    }

    public function index(): View
    {
        $theaters = Theater::withCount('seats')->orderBy('Theater_ID')->get();

        return view('admin.admin_theater', compact('theaters'));
    }

    public function edit(int $theater): View
    {
        $selectedTheater = Theater::with(['seats' => function ($query) {
            $query->orderBy('pos_y')->orderBy('pos_x');
        }])->findOrFail($theater);
        $bookedSeatIds = DB::table('ticket')->pluck('Seat_ID')->all();

        return view('admin.admin_theater_seat', compact('selectedTheater', 'bookedSeatIds'));
    }
}
