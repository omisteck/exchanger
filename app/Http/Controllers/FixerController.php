<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FixerService;


class FixerController extends Controller
{
    protected $fixerService;
    public function __construct(FixerService $fixerService)
    {
        $this->fixerService = $fixerService;
    }
   
    public function changeCurrency(Request $request)
    {
        return $this->fixerService->changeBaseCurrency($request);
    }

    public function rateList(Request $request)
    {
        return $this->fixerService->rateList($request);
    }

}
