<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ThresholdAlert;
use App\Services\FixerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{

    public function __invoke()
    {
       
        // select all user with thresholds settings
        $users = DB::table('users')
        ->join('thresholds', 'users.id', '=', 'thresholds.user_id')
        ->select(['users.email', 'users.base_currency', 'thresholds.value', 'thresholds.codition', 'thresholds.other_currency'])
        ->groupBy(['users.email',"users.base_currency", 'thresholds.value', 'thresholds.codition', 'thresholds.other_currency'])
        ->get();

        //group them by there base currency to make logic fast
        $users = $users->groupBy("base_currency");
        $this->checkEachThreshold($users);
    }
    public function checkEachThreshold($users)
    {
        
        foreach($users as $group => $data){
            $response = (new fixerService())->makeCurl("GET", "/latest", ["base" => $group]);
            $decoded_response = json_decode($response->getBody());
            if($decoded_response->success == false){
                $message =  $this->getStatusMessage($decoded_response->error->code);
                return $this->successMessage([], 422, $message);
            }
            $rates = collect($decoded_response->rates)->toArray();
            
            foreach($data as $singleThreshold){

                switch($singleThreshold->codition){
                    case "=" :
                        if( $rates[$singleThreshold->other_currency] == $singleThreshold->value){
                            //send mail
                            /* NOTE------------
                            the mail send will have been done with queue but since this is not going to production am justing it*/
                            Mail::to($singleThreshold->email)->send(new ThresholdAlert($singleThreshold));
                        }
                    break;
                    case "<":
                        if( $rates[$singleThreshold->other_currency] < $singleThreshold->value){
                            //send mail
                            /* NOTE------------
                            the mail send will have been done with queue but since this is not going to production am justing it*/
                            Mail::to($singleThreshold->email)->send(new ThresholdAlert($singleThreshold));
                        }
                    break;
                    case ">":
                        if( $rates[$singleThreshold->other_currency] > $singleThreshold->value){
                            //send mail
                            /* NOTE------------
                            the mail send will have been done with queue but since this is not going to production am justing it*/
                            Mail::to($singleThreshold->email)->send(new ThresholdAlert($singleThreshold));
                        }
                    break;

                }
            };
            
        }
    }
}
