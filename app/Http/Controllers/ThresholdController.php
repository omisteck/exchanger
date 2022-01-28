<?php

namespace App\Http\Controllers;

use App\Models\Threshold;
use App\Services\ThresholdService;
use App\Http\Requests\StoreThresholdRequest;

class ThresholdController extends Controller
{  
    protected $thresholdService;
    
    public function __construct(ThresholdService $thresholdService)
    {
        $this->thresholdService = $thresholdService;
    }
   
    public function store(StoreThresholdRequest $request)
    {
       return $this->thresholdService->storeThreshold($request);
    }

}
