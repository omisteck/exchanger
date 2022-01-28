<?php
namespace App\Services;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService{
    use ResponseTrait;

    public function storeUser($request)
    {
        $created_user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
        ]);    
        return $this->successMessage($created_user, 201, 'User register successfully!');
    }

    public function loginUser($request)
    {

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth succesfull')->accessToken;
                $response = ['token' => $token];
                return $this->successMessage($response, 200, 'User login successfully!');
            } else {
                return $this->successMessage([], 422, 'Password mismatch!');
            }
        } else {
            return $this->successMessage([], 422, 'User does not exist!');
        }

    }


    public function logoutUser($request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return $this->successMessage([], 200, 'You have been successfully logged out!');
    }

    
}
?>