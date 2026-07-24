<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsorTier;
use Illuminate\Http\Request;

class SponsorTierController extends Controller
{
    public function index()
    {
        $tiers = SponsorTier::ordered()->get();
        return view('admin.sponsor-tiers.index', compact('tiers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'badge_emoji'   => ['nullable', 'string', 'max:10'],
            'min_actives'   => ['required', 'integer', 'min:0'],
            'bonus_usd'     => ['required', 'numeric', 'min:0'],
            'commission_l1' => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_l2' => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_l3' => ['required', 'numeric', 'min:0', 'max:100'],
            'order'         => ['required', 'integer', 'min:1'],
        ]);

        SponsorTier::create($validated);

        return back()->with('success', 'Tier created.');
    }

    public function update(Request $request, SponsorTier $sponsorTier)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'badge_emoji'   => ['nullable', 'string', 'max:10'],
            'min_actives'   => ['required', 'integer', 'min:0'],
            'bonus_usd'     => ['required', 'numeric', 'min:0'],
            'commission_l1' => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_l2' => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_l3' => ['required', 'numeric', 'min:0', 'max:100'],
            'order'         => ['required', 'integer', 'min:1'],
        ]);

        $sponsorTier->update($validated);

        return back()->with('success', 'Tier updated.');
    }

    public function destroy(SponsorTier $sponsorTier)
    {
        $sponsorTier->delete();
        return back()->with('success', 'Tier deleted.');
    }
}