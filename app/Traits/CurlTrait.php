<?php 
namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait CurlTrait{
    public function makeCurlRequest($method, $endpoint, $params, $accessKey)
    {
        
        $params = array_merge(["access_key"=>$accessKey], $params);
        $response = Http::get($endpoint, $params);
        return $response;
    }
}

?>