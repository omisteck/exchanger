<?php
namespace App\Traits;

trait ResponseTrait{

    public function successMessage($data, $code, $message){
        return response()->json([
            'statuscode' => $code,
            'status' => $message,
            'data' => $data
        ], $code);
    }

}

?>