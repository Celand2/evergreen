<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\SponsorTier;
use App\Models\SponsorTierAchievement;
use App\Models\User;
use App\Models\UserVip;

class ReferralService
{
    /**
     * Appelé après approbation d'un dépôt.
     * Verse les commissions ET vérifie l'activation du filleul.
     */
    public function processCommissions(Deposit $deposit): void
    {
        $user = $deposit->user;
        $amount = $deposit->amount_usd;

        if ($user->referred_by) {
            $level1 = User::find($user->referred_by);

            if ($level1) {
                $this->payCommission($level1, $amount, 1, $user->id);

                $level2 = $level1->referred_by ? User::find($level1->referred_by) : null;
                if ($level2) {
                    $this->payCommission($level2, $amount, 2, $user->id);

                    $level3 = $level2->referred_by ? User::find($level2->referred_by) : null;
                    if ($level3) {
                        $this->payCommission($level3, $amount, 3, $user->id);
                    }
                }
            }
        }

        // Vérifie si ce dépôt rend le filleul "actif"
        $this->checkActivation($user);
    }

    /**
     * Appelé après achat d'un VIP.
     * Un VIP actif peut aussi rendre le filleul "actif".
     */
    public function processVipActivation(UserVip $userVip): void
    {
        $this->checkActivation($userVip->user);
    }

    /**
     * Verse une commission dynamique selon le tier actuel du referrer.
     */
    private function payCommission(User $referrer, float $amount, int $level, int $referredId): void
    {
        if ($referrer->is_frozen) {
            return; // compte gelé pour fraude → pas de commission
        }

        $tier = $referrer->currentSponsorTier();

        $rate = match ($level) {
            1 => $tier->commission_l1,
            2 => $tier->commission_l2,
            3 => $tier->commission_l3,
            default => 0,
        };

        if ($rate <= 0) {
            return;
        }

        $commission = $amount * ($rate / 100);

        $referrer->balance_retirable += $commission;
        $referrer->save();

        Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referredId,
            'commission'  => $commission,
            'level'       => $level,
            'is_active'   => false, // sert seulement au ledger de commission ici
        ]);
    }

    /**
     * Vérifie si un filleul devient "actif" (dépôt >= $5 OU VIP actif).
     * Si oui, marque la relation, notifie le parrain, vérifie le palier.
     */
    public function checkActivation(User $referredUser): void
    {
        if (! $referredUser->referred_by) {
            return;
        }

        $referrer = User::find($referredUser->referred_by);
        if (! $referrer || $referrer->is_frozen) {
            return;
        }

        if (! $referredUser->qualifiesAsActive()) {
            return;
        }

        // Trouve ou crée la relation "activation" niveau 1
        $relation = Referral::firstOrCreate(
            [
                'referrer_id' => $referrer->id,
                'referred_id' => $referredUser->id,
                'level'       => 1,
                'commission'  => 0, // ligne spéciale "relation d'activation", commission=0
            ],
            ['is_active' => false]
        );

        if ($relation->is_active) {
            return; // déjà comptabilisé
        }

        $relation->update(['is_active' => true]);

        Notification::create([
            'user_id' => $referrer->id,
            'title'   => 'New active referral 🎉',
            'body'    => "{$referredUser->name} just became active! You're at {$referrer->activeReferralsCount()} active referrals.",
        ]);

        $this->checkTierUpgrade($referrer);
    }

    /**
     * Vérifie si le referrer vient de franchir un nouveau palier.
     * Si oui, verse le bonus et notifie.
     */
    private function checkTierUpgrade(User $referrer): void
    {
        $activeCount = $referrer->activeReferralsCount();
        $newTier = SponsorTier::forActiveCount($activeCount);

        $alreadyAchieved = SponsorTierAchievement::where('user_id', $referrer->id)
            ->where('sponsor_tier_id', $newTier->id)
            ->exists();

        if ($alreadyAchieved || $newTier->bonus_usd <= 0) {
            return;
        }

        SponsorTierAchievement::create([
            'user_id'         => $referrer->id,
            'sponsor_tier_id' => $newTier->id,
            'bonus_usd'       => $newTier->bonus_usd,
            'achieved_at'     => now(),
        ]);

        $referrer->balance_retirable += $newTier->bonus_usd;
        $referrer->save();

        Notification::create([
            'user_id' => $referrer->id,
            'title'   => "Congratulations! You're now {$newTier->name} {$newTier->badge_emoji}",
            'body'    => "You've unlocked a bonus of \${$newTier->bonus_usd} and your commissions are now {$newTier->commission_l1}% - {$newTier->commission_l2}% - {$newTier->commission_l3}%!",
        ]);
    }
}