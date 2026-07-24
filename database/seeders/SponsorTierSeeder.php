<?php

namespace Database\Seeders;

use App\Models\SponsorTier;
use Illuminate\Database\Seeder;

class SponsorTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            ['name' => 'Standard',         'badge_emoji' => '⭐', 'min_actives' => 0,   'bonus_usd' => 0,    'commission_l1' => 11, 'commission_l2' => 3, 'commission_l3' => 1, 'order' => 1],
            ['name' => 'Bronze Leader',    'badge_emoji' => '🥉', 'min_actives' => 10,  'bonus_usd' => 7.50,  'commission_l1' => 11, 'commission_l2' => 3, 'commission_l3' => 1, 'order' => 2],
            ['name' => 'Silver Leader',    'badge_emoji' => '🥈', 'min_actives' => 30,  'bonus_usd' => 25.00, 'commission_l1' => 12, 'commission_l2' => 3, 'commission_l3' => 1, 'order' => 3],
            ['name' => 'Gold Manager',     'badge_emoji' => '🥇', 'min_actives' => 80,  'bonus_usd' => 50.00, 'commission_l1' => 13, 'commission_l2' => 4, 'commission_l3' => 1, 'order' => 4],
            ['name' => 'Diamond Director', 'badge_emoji' => '💎', 'min_actives' => 150, 'bonus_usd' => 100.00, 'commission_l1' => 15, 'commission_l2' => 5, 'commission_l3' => 2, 'order' => 5],
        ];

        foreach ($tiers as $tier) {
            SponsorTier::updateOrCreate(['min_actives' => $tier['min_actives']], $tier);
        }
    }
}