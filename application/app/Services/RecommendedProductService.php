<?php

namespace App\Services;

use App\Models\RecommendedProduct;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecommendedProductService
{
    const CACHE_KEY = 'recommended_products';

    public function getList(): Collection
    {
        return Cache::remember(self::CACHE_KEY, $this->getTenMinutes(), function () {
            DB::select('select sleep(3);');

            return RecommendedProduct::getList();
        });
    }

    private function getTenMinutes(): int
    {
        $interval = CarbonInterval::minutes(10);

        return $interval->totalSeconds;
    }
}
