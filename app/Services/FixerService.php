<?php
namespace App\Services;

use App\Models\User;
use App\Traits\CurlTrait;
use App\Traits\ResponseTrait;

class FixerService{
    use ResponseTrait, CurlTrait;

    public function makeCurl($method, $endpoint, $params)
    {
        $endpoint = config("keys.Fixer.Base_URL").$endpoint;
        return $this->makeCurlRequest($method, $endpoint, $params, config("keys.Fixer.access_key"));    
    }

    public function getBaseCurrency(){
        return auth()->user()->base_currency;
    }

    public function getStatusMessage($statusCode)
    {

        return $this->statusList()[$statusCode];
    }

    public function changeBaseCurrency($request)
    {
        $response = $this->makeCurl('GET', '/latest', ["base" => $request->currency_short_code]);
        $decoded_response = json_decode($response->getBody());
        if($decoded_response->success == false){
            $message =  $this->getStatusMessage($decoded_response->error->code);
            return $this->successMessage([], 422, $message);
        }
        //saving the current base_currency to database
        $loginUser = User::find(auth()->user()->id);
        $loginUser->base_currency = $request->currency_short_code;
        $loginUser->update();

        return $this->successMessage($decoded_response->rates, 201, 'Base currency changed successfully!');
    }


    public function rateList()
    {
        $response = $this->makeCurl('GET', '/latest', ["base" => $this->getBaseCurrency(), "symbols" => "GBP,JPY,EUR"]);
        $decoded_response = json_decode($response->getBody());
        if($decoded_response->success == false){
            $message =  $this->getStatusMessage($decoded_response->error->code);
            return $this->successMessage([], 422, $message);
        }
        return $this->successMessage($decoded_response, 201, 'Rate list!');
    }


    public function statusList()
    {
        return [
            "404" =>	"The requested resource does not exist.",
            "101" => "No API Key was specified or an invalid API Key was specified.",
            "103"	=> "The requested API endpoint does not exist.",
            "104"=> 	"The maximum allowed API amount of monthly API requests has been reached.",
            "105"	=> "The current subscription plan does not support this API endpoint.",
            "106"	=> "The current request did not return any results.",
            "102"	=> "The account this API request is coming from is inactive.",
            "201"	=> "An invalid base currency has been entered.",
            "202"	=> "One or more invalid symbols have been specified.",
            "301"	=> "No date has been specified. [historical]",
            "302"	=> "An invalid date has been specified. [historical, convert]",
            "403"	=> "No or an invalid amount has been specified. [convert]",
            "501"	=> "No or an invalid timeframe has been specified. [timeseries]",
            "502"	=> "No or an invalid start_date has been specified. [timeseries, fluctuation]",
            "503"	=> "No or an invalid end_date has been specified. [timeseries, fluctuation]",
            "504"	=> "An invalid timeframe has been specified. [timeseries, fluctuation]",
            "505" =>	"The specified timeframe is too long, exceeding 365 days. [timeseries, fluctuation]"
        ];
    }



   
}
?>