<?php
namespace App\Services;

use App\Models\User;
use App\Traits\ResponseTrait;

class ThresholdService{
    use ResponseTrait;

    public function storeThreshold($request)
    {
        $created = User::find(auth()->user()->id)->thresholds()->create($request->only(['other_currency', 'codition', 'value']));
        return $this->successMessage($created, 201, 'You threshold as been successfully!');
    }
   
}
?>