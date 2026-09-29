<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MlRankCalculator;
use App\Models\JokiMlService;

class JokiMlOrderForm extends Component
{
    public JokiMlService $jokiMlService;
    
    public string $current_rank = 'master';
    public $current_division = 1;
    public $current_stars = 0;
    
    public string $target_rank = 'legend';
    public $target_division = 1;
    public $target_stars = 0;
    
    public $priceBreakdown = [];
    public $totalStars = 0;
    public $totalPrice = 0;
    
    protected $calculator;
    
    public function mount(JokiMlService $jokiMlService)
    {
        $this->jokiMlService = $jokiMlService;
        $this->calculator = new MlRankCalculator();

        // Keep the buyer's input when checkout validation redirects back here.
        $this->current_rank = old('current_rank', $this->current_rank);
        $this->current_division = (int) old('current_division', $this->current_division);
        $this->current_stars = (int) old('current_stars', $this->current_stars);
        $this->target_rank = old('target_rank', $this->target_rank);
        $this->target_division = (int) old('target_division', $this->target_division);
        $this->target_stars = (int) old('target_stars', $this->target_stars);
        
        // Calculate initial price (this will trigger property getters)
        $this->calculatePrice();
    }
    
    private function getCalculator()
    {
        return $this->calculator ?: $this->calculator = new MlRankCalculator();
    }
    
    public function calculatePrice()
    {
        $this->normalizeRankInputs();
        $this->resetErrorBag(['current_rank', 'current_stars', 'target_rank', 'target_stars']);
        $this->totalStars = 0;
        $this->totalPrice = 0;
        $this->priceBreakdown = [];

        if (!$this->validateRankSelection()) {
            return;
        }
        
        $calculator = $this->getCalculator();
        $priceConfig = $this->jokiMlService->price_per_star_config;

        if ($priceConfig) {
            $result = $calculator->calculatePriceWithBreakdown(
                $this->current_rank,
                $this->current_division,
                $this->current_stars,
                $this->target_rank,
                $this->target_division,
                $this->target_stars,
                $priceConfig
            );
            
            $this->totalStars = $result['total_stars'];
            $this->totalPrice = $result['total_price'];
            $this->priceBreakdown = $result['breakdown'];
        } else {
            $this->totalStars = $calculator->calculateStarDifference(
                $this->current_rank,
                $this->current_division,
                $this->current_stars,
                $this->target_rank,
                $this->target_division,
                $this->target_stars
            );
            
            $this->totalPrice = null;
            $this->priceBreakdown = [];
        }
    }

    private function normalizeRankInputs(): void
    {
        $this->current_division = $this->normalizedDivision($this->current_rank, $this->current_division);
        $this->target_division = $this->normalizedDivision($this->target_rank, $this->target_division);
        $this->current_stars = max(0, (int) ($this->current_stars ?? 0));

        $targetMinimum = isset(MlRankCalculator::RANKS[$this->target_rank])
            ? MlRankCalculator::getRelativeMinStars($this->target_rank)
            : 0;
        $this->target_stars = ($this->target_stars === null || $this->target_stars === '')
            ? $targetMinimum
            : max(0, (int) $this->target_stars);
    }

    /**
     * Mythic and higher do not have a division selector. Livewire can retain
     * the division from the previously selected normal rank while a rank
     * change request is in flight, so always canonicalize it to division 1.
     */
    private function normalizedDivision(string $rank, mixed $division): int
    {
        $config = MlRankCalculator::RANKS[$rank] ?? null;
        if (! $config || $config['divisions'] <= 1) {
            return 1;
        }

        return min($config['divisions'], max(1, (int) ($division ?? 1)));
    }
    
    public function updatedCurrentRank()
    {
        $this->current_division = 1;
        $this->current_stars = 0;
        $this->calculatePrice();
    }
    
    public function updatedTargetRank()
    {
        $this->target_division = 1;
        $this->target_stars = MlRankCalculator::getRelativeMinStars($this->target_rank);
        $this->calculatePrice();
    }
    
    public function updatedCurrentDivision()
    {
        $this->current_stars = 0;
        $this->calculatePrice();
    }
    
    public function updatedTargetDivision()
    {
        $this->target_stars = MlRankCalculator::getRelativeMinStars($this->target_rank);
        $this->calculatePrice();
    }
    
    public function updatedCurrentStars()
    {
        $this->calculatePrice();
    }
    
    public function updatedTargetStars()
    {
        $this->calculatePrice();
    }
    
    private function validateRankSelection()
    {
        $calculator = $this->getCalculator();
        
        // Validate current rank
        if (!$calculator->validateRank($this->current_rank, $this->current_division, $this->current_stars)) {
            $this->addError('current_rank', 'Rank saat ini tidak valid.');
            return false;
        }
        
        // Validate target rank
        if (!$calculator->validateRank($this->target_rank, $this->target_division, $this->target_stars)) {
            $this->addError('target_rank', 'Rank tujuan tidak valid.');
            return false;
        }
        
        // Validate progression (target must be higher than current)
        $currentPos = $calculator->toAbsolutePosition($this->current_rank, $this->current_division, $this->current_stars);
        $targetPos = $calculator->toAbsolutePosition($this->target_rank, $this->target_division, $this->target_stars);
        
        if ($targetPos <= $currentPos) {
            $this->addError('target_rank', 'Rank tujuan harus lebih tinggi dari rank saat ini.');
            return false;
        }
        
        return true;
    }
    
    public function getDivisionOptionsProperty()
    {
        $rankConfig = MlRankCalculator::RANKS[$this->current_rank] ?? null;
        if (!$rankConfig || $rankConfig['divisions'] <= 1) {
            return [];
        }
        return MlRankCalculator::getDivisionOptions($this->current_rank);
    }
    
    public function getTargetDivisionOptionsProperty()
    {
        $rankConfig = MlRankCalculator::RANKS[$this->target_rank] ?? null;
        if (!$rankConfig || $rankConfig['divisions'] <= 1) {
            return [];
        }
        return MlRankCalculator::getDivisionOptions($this->target_rank);
    }
    
    public function getCurrentMaxStarsProperty()
    {
        return MlRankCalculator::getMaxStars($this->current_rank);
    }
    
    public function getTargetMaxStarsProperty()
    {
        return MlRankCalculator::getMaxStars($this->target_rank);
    }
    
    public function getRankOptionsProperty()
    {
        return MlRankCalculator::getRankOptions();
    }
    
    public function getTargetRankOptionsProperty()
    {
        return MlRankCalculator::getRankOptions();
    }
    
    public function render()
    {
        return view('livewire.joki-ml-order-form');
    }
}
