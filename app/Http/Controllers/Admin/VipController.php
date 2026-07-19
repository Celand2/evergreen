<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vip;
use Illuminate\Http\Request;

class VipController extends Controller
{
    public function index()
    {
        $vips = Vip::paginate(20);
        return view('admin.vips.index', compact('vips'));
    }

    public function create()
    {
        return view('admin.vips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'daily_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Vip::create($validated);

        return redirect()->route('admin.vips.index')->with('success', 'VIP created successfully.');
    }

    public function edit(Vip $vip)
    {
        return view('admin.vips.edit', compact('vip'));
    }

    public function update(Request $request, Vip $vip)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'daily_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vip->update($validated);

        return redirect()->route('admin.vips.index')->with('success', 'VIP updated successfully.');
    }

    public function destroy(Vip $vip)
    {
        $vip->delete();
        return redirect()->route('admin.vips.index')->with('success', 'VIP deleted successfully.');
    }
}