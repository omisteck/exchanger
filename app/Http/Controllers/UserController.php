<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;    
    }
   
    public function register(RegisterUserRequest $request)
    {
        return $this->userService->storeUser($request);
    }

    
    public function login(LoginUserRequest $request)
    {
        return $this->userService->loginUser($request);
    }

    public function logout(Request $request)
    {
        return $this->userService->logoutUser($request);
    }
   
}
