<?php

namespace App\Services;

class MlRankCalculator
{
    const RANKS = [
        'warrior' => [
            'order' => 1,
            'divisions' => 3,
            'stars_per_division' => 3,
            'label' => 'Warrior',
        ],
        'elite' => [
            'order' => 2,
            'divisions' => 3,
            'stars_per_division' => 4,
            'label' => 'Elite',
        ],
        'master' => [
            'order' => 3,
            'divisions' => 4,
            'stars_per_division' => 4,
            'label' => 'Master',
        ],
        'grandmaster' => [
            'order' => 4,
            'divisions' => 5,
            'stars_per_division' => 5,
            'label' => 'Grandmaster',
        ],
        'epic' => [
            'order' => 5,
            'divisions' => 5,
            'stars_per_division' => 5,
            'label' => 'Epic',
        ],
        'legend' => [
            'order' => 6,
            'divisions' => 5,
            'stars_per_division' => 5,
            'label' => 'Legend',
        ],
        'mythic' => [
            'order' => 7,
            'divisions' => 1,
            'stars_per_division' => 25,
            'label' => 'Mythic',
        ],
        'mythical_honor' => [
            'order' => 8,
            'divisions' => 1,
            'stars_per_division' => 25,
            'label' => 'Mythical Honor',
        ],
        'mythical_glory' => [
            'order' => 9,
            'divisions' => 1,
            'stars_per_division' => 50,
            'label' => 'Mythical Glory',
        ],
        'mythical_immortal' => [
            'order' => 10,
            'divisions' => 1,
            'stars_per_division' => 100,
            'label' => 'Mythical Immortal',
        ],
    ];

    public function validateRank(string $rank, int $division, int $stars): bool
    {
        $rank = strtolower($rank);
        
        if (!isset(self::RANKS[$rank])) {
            return false;
        }
        
        $config = self::RANKS[$rank];
        
        // Validate division
        if ($division < 1 || $division > $config['divisions']) {
            return false;
        }
        
        // For Mythic+ ranks (single division), validate stars against relative range
        if ($config['divisions'] <= 1) {
            $minStars = self::getRelativeMinStars($rank);
            $maxStars = self::getRelativeMaxStars($rank);
            if ($stars < $minStars || $stars > $maxStars) {
                return false;
            }
        } else {
            // For normal ranks, stars is relative (0 to max-1)
            if ($stars < 0 || $stars >= $config['stars_per_division']) {
                return false;
            }
        }
        
        return true;
    }

    public function toAbsolutePosition(string $rank, int $division, int $stars): int
    {
        $rank = strtolower($rank);
        $config = self::RANKS[$rank];
        
        // Calculate total stars from all previous ranks
        $totalFromPreviousRanks = 0;
        foreach (self::RANKS as $r => $cfg) {
            if ($cfg['order'] < $config['order']) {
                $totalFromPreviousRanks += $cfg['divisions'] * $cfg['stars_per_division'];
            }
        }
        
        // Mythic+ stars are cumulative. They all share the same base: the
        // total stars required to reach Mythic from the normal ranks.
        if ($config['order'] >= 7) {
            $mythicBase = 0;
            foreach (self::RANKS as $cfg) {
                if ($cfg['order'] < self::RANKS['mythic']['order']) {
                    $mythicBase += $cfg['divisions'] * $cfg['stars_per_division'];
                }
            }

            return $mythicBase + $stars;
        }
        
        // For normal ranks (Warrior-Legend), calculate position within rank
        // Stars are 0-based (0 = no stars, max = stars_per_division - 1)
        // Example: Warrior with 3 stars_per_division: valid stars = 0, 1, 2
        
        $divisionsCompleted = $config['divisions'] - $division;
        // Each completed division contributes full stars_per_division stars
        // Current division contributes $stars stars
        $starsInCurrentRank = ($divisionsCompleted * $config['stars_per_division']) + $stars;
        
        return $totalFromPreviousRanks + $starsInCurrentRank;
    }

    public function calculateStarDifference(
        string $currentRank, int $currentDivision, int $currentStars,
        string $targetRank, int $targetDivision, int $targetStars
    ): int {
        $currentPos = $this->toAbsolutePosition($currentRank, $currentDivision, $currentStars);
        $targetPos = $this->toAbsolutePosition($targetRank, $targetDivision, $targetStars);
        
        return max(0, $targetPos - $currentPos);
    }

    public function calculatePriceWithBreakdown(
        string $currentRank, int $currentDivision, int $currentStars,
        string $targetRank, int $targetDivision, int $targetStars,
        array $pricePerStar
    ): array {
        $currentRank = strtolower($currentRank);
        $targetRank = strtolower($targetRank);
        
        $currentAbsolute = $this->toAbsolutePosition($currentRank, $currentDivision, $currentStars);
        $targetAbsolute = $this->toAbsolutePosition($targetRank, $targetDivision, $targetStars);
        
        // Validate that target is greater than current
        if ($targetAbsolute <= $currentAbsolute) {
            return [
                'total_stars' => 0,
                'total_price' => 0,
                'breakdown' => [],
            ];
        }
        
        $breakdown = [];
        $totalPrice = 0;
        $totalStars = 0;
        
        $currentOrder = self::RANKS[$currentRank]['order'];
        $targetOrder = self::RANKS[$targetRank]['order'];
        
        $configCurrent = self::RANKS[$currentRank];
        $configTarget = self::RANKS[$targetRank];
        
        foreach (self::RANKS as $rankKey => $rankConfig) {
            if ($rankConfig['order'] < $currentOrder || $rankConfig['order'] > $targetOrder) {
                continue;
            }
            
            $rankMinAbsolute = self::getMinStars($rankKey);
            $rankMaxAbsolute = self::getMaxStars($rankKey);

            // The Immortal input starts at 100, but that value is the
            // promotion boundary from Mythical Glory, not an earned
            // Immortal star. Its payable interval therefore begins after
            // the boundary. Without this adjustment, every route ending at
            // Immortal charges one star more than the actual rank distance.
            if ($rankKey === 'mythical_immortal') {
                $rankMinAbsolute++;
            }
            
            // For Mythic+ ranks, need special handling:
            // - Input stars are already relative to the rank (0-24, 25-49, 50-99, 100+)
            // - But toAbsolutePosition adds offset, so ranks are: 112-136, 162-186, 212-261
            // - The boundary stars (24, 49, 99) should be excluded from calculation
            $isMythicPlus = in_array($rankKey, ['mythic', 'mythical_honor', 'mythical_glory', 'mythical_immortal']);
            
            // Calculate overlap - stars needed FROM current TO target
            // Start position depends on:
            // 1. If this is current rank AND current has stars, start from next position
            // 2. If this is current rank AND current has no stars, start from current position
            // 3. If this is NOT current rank, start from rank minimum
            
            if ($rankConfig['order'] == $currentOrder && $currentStars > 0) {
                // Current rank with stars: start from next position
                $overlapStart = $currentAbsolute + 1;
            } elseif ($rankConfig['order'] == $currentOrder && $currentStars == 0) {
                // Current rank with no stars: start from current position
                $overlapStart = $currentAbsolute;
            } else {
                // Not current rank: start from rank minimum
                $overlapStart = $rankMinAbsolute;
            }
            
            if ($rankConfig['order'] == $targetOrder) {
                // For target rank, check if it's at the start (div=max, stars=0)
                $isTargetAtStart = ($targetDivision == $configTarget['divisions'] && $targetStars == 0);
                
                if ($isTargetAtStart) {
                    // Target is at first position of the rank, no stars in this rank
                    $overlapEnd = $rankMinAbsolute - 1;
                } else {
                    // Target is within the rank
                    // If target_stars = 0, it means first position of division (not yet earned)
                    if ($targetStars == 0) {
                        $overlapEnd = $targetAbsolute - 1;
                    } else {
                        $overlapEnd = $targetAbsolute;
                    }
                }
            } else {
                // Non-target ranks: can go beyond rank max to reach next rank
                // This handles the case where current is at rank max (e.g., Warrior I 2★)
                // and needs to earn stars to reach next rank
                $overlapEnd = min($rankMaxAbsolute, $targetAbsolute - 1);
            }
            
            $starsInThisRank = max(0, $overlapEnd - $overlapStart + 1);
            
            // Special case: if current is at rank max and target is beyond this rank,
            // need to add the "promoting star" (the star that advances to next rank)
            if ($starsInThisRank == 0 && $currentAbsolute == $rankMaxAbsolute && 
                $rankConfig['order'] == $currentOrder && $targetAbsolute > $rankMaxAbsolute) {
                $starsInThisRank = 1;
            }
            
            if ($starsInThisRank > 0) {
                $priceForThisRank = $starsInThisRank * ($pricePerStar[$rankKey] ?? 0);
                
                $breakdown[] = [
                    'rank' => $rankConfig['label'],
                    'rank_key' => $rankKey,
                    'stars' => $starsInThisRank,
                    'price_per_star' => $pricePerStar[$rankKey] ?? 0,
                    'subtotal' => $priceForThisRank,
                ];
                
                $totalStars += $starsInThisRank;
                $totalPrice += $priceForThisRank;
            }
        }
        
        return [
            'total_stars' => $totalStars,
            'total_price' => $totalPrice,
            'breakdown' => $breakdown,
        ];
    }

    private function calculateStarsInSameRank($config, $startDiv, $startStars, $endDiv, $endStars): int
    {
        $startPos = ($config['divisions'] - $startDiv) * $config['stars_per_division'] + $startStars;
        $endPos = ($config['divisions'] - $endDiv) * $config['stars_per_division'] + $endStars;
        return max(0, $endPos - $startPos);
    }

    private function calculateStarsFromPosition($config, $currentDiv, $currentStars): int
    {
        $totalStarsInRank = $config['divisions'] * $config['stars_per_division'];
        $currentPosInRank = ($config['divisions'] - $currentDiv) * $config['stars_per_division'] + $currentStars;
        return $totalStarsInRank - $currentPosInRank;
    }

    private function calculateStarsToPosition($config, $targetDiv, $targetStars): int
    {
        return ($config['divisions'] - $targetDiv) * $config['stars_per_division'] + $targetStars;
    }

    public static function getRankOptions(): array
    {
        return array_map(fn($r) => $r['label'], self::RANKS);
    }

    public static function getRankKeyOptions(): array
    {
        return array_keys(self::RANKS);
    }

    public static function getDivisionOptions(string $rank): array
    {
        $rank = strtolower($rank);
        if (!isset(self::RANKS[$rank])) {
            return [];
        }
        
        $divisions = self::RANKS[$rank]['divisions'];
        $options = [];
        
        for ($i = $divisions; $i >= 1; $i--) {
            $romanNumeral = ['I', 'II', 'III', 'IV', 'V'][$i - 1] ?? '';
            $options[$i] = $romanNumeral;
        }
        
        return $options;
    }

    public static function getMinStars(string $rank): int
    {
        $rank = strtolower($rank);
        $config = self::RANKS[$rank];
        
        // Calculate offset from all previous ranks
        $offset = 0;
        foreach (self::RANKS as $r => $cfg) {
            if ($cfg['order'] < $config['order']) {
                $offset += $cfg['divisions'] * $cfg['stars_per_division'];
            }
        }
        
        // Mythic+ stars are cumulative and share the normal-rank base.
        if ($rank === 'mythic') {
            return self::getNormalRanksStarTotal() + 0;
        } elseif ($rank === 'mythical_honor') {
            return self::getNormalRanksStarTotal() + 25;
        } elseif ($rank === 'mythical_glory') {
            return self::getNormalRanksStarTotal() + 50;
        } elseif ($rank === 'mythical_immortal') {
            return self::getNormalRanksStarTotal() + 100;
        }
        
        return $offset;
    }
    
    public static function getMaxStars(string $rank): int
    {
        $rank = strtolower($rank);
        $config = self::RANKS[$rank];
        
        // Calculate offset from all previous ranks
        $offset = 0;
        foreach (self::RANKS as $r => $cfg) {
            if ($cfg['order'] < $config['order']) {
                $offset += $cfg['divisions'] * $cfg['stars_per_division'];
            }
        }
        
        // Mythic+ stars are cumulative and share the normal-rank base.
        if ($rank === 'mythic') {
            return self::getNormalRanksStarTotal() + 24;
        } elseif ($rank === 'mythical_honor') {
            return self::getNormalRanksStarTotal() + 49;
        } elseif ($rank === 'mythical_glory') {
            return self::getNormalRanksStarTotal() + 99;
        } elseif ($rank === 'mythical_immortal') {
            return self::getNormalRanksStarTotal() + 5863;
        }
        
        // For normal ranks, max is at the end of all divisions
        return $offset + ($config['divisions'] * $config['stars_per_division']) - 1;
    }

    private static function getNormalRanksStarTotal(): int
    {
        $total = 0;

        foreach (self::RANKS as $config) {
            if ($config['order'] < self::RANKS['mythic']['order']) {
                $total += $config['divisions'] * $config['stars_per_division'];
            }
        }

        return $total;
    }
    
    public static function getRelativeStars(string $rank, int $absoluteStars): int
    {
        // Calculate how many stars from this rank to reach the target
        // For Mythic+: stars are cumulative, so we calculate relative to previous ranks
        $rank = strtolower($rank);
        
        if ($rank === 'mythic') {
            return $absoluteStars; // 0-24
        } elseif ($rank === 'mythical_honor') {
            return max(0, $absoluteStars - 24); // 25-49 → relative 1-25
        } elseif ($rank === 'mythical_glory') {
            return max(0, $absoluteStars - 49); // 50-99 → relative 1-50
        } elseif ($rank === 'mythical_immortal') {
            return max(0, $absoluteStars - 99); // 100-5863 → relative 1-5763
        }
        
        // For normal ranks (Warrior-Legend), stars is always relative to 0
        return $absoluteStars;
    }
    
    public static function getRelativeMinStars(string $rank): int
    {
        $rank = strtolower($rank);
        
        if ($rank === 'mythic') {
            return 0;
        } elseif ($rank === 'mythical_honor') {
            return 25;
        } elseif ($rank === 'mythical_glory') {
            return 50;
        } elseif ($rank === 'mythical_immortal') {
            return 100;
        }
        
        return 0;
    }
    
    public static function getRelativeMaxStars(string $rank): int
    {
        $rank = strtolower($rank);
        
        if ($rank === 'mythic') {
            return 24;
        } elseif ($rank === 'mythical_honor') {
            return 49;
        } elseif ($rank === 'mythical_glory') {
            return 99;
        } elseif ($rank === 'mythical_immortal') {
            return 5863;
        }
        
        $config = self::RANKS[$rank];
        return $config['stars_per_division'] - 1;
    }
    
    public static function getAllRanks(): array
    {
        return self::RANKS;
    }
}
