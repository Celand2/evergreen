<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LuckyWheelSegment;
use Illuminate\Http\Request;

class LuckyWheelSegmentController extends Controller
{
    public function index()
    {
        $segments = LuckyWheelSegment::all();
        return view('admin.lucky-wheel-segments.index', compact('segments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount_usd' => ['required', 'numeric', 'min:0.01'],
        ]);
        $validated['is_active'] = true;

        LuckyWheelSegment::create($validated);

        return back()->with('success', 'Segment added.');
    }

    public function update(Request $request, LuckyWheelSegment $luckyWheelSegment)
    {
        $validated = $request->validate([
            'amount_usd' => ['required', 'numeric', 'min:0.01'],
            'is_active'  => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');

        $luckyWheelSegment->update($validated);

        return back()->with('success', 'Segment updated.');
    }

    public function destroy(LuckyWheelSegment $luckyWheelSegment)
    {
        $luckyWheelSegment->delete();
        return back()->with('success', 'Segment deleted.');
    }
}