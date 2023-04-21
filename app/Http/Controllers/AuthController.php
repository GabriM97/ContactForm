<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Responses\CustomJsonResponse;
use App\Http\Responses\CustomErrorJsonResponse;

class AuthController extends Controller
{    
    /**
     * @param  UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        // log in the user in case of listeners waiting for this event to happen
        Auth::login($user);

        $tokenData = $user->createToken('app')->toArray();

        return (new CustomJsonResponse($tokenData))->get();
    }
    
    /**
     * @param  UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $email = $validated['email'];
        $password = $validated['password'];

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $tokenData = Auth::user()->createToken('app')->toArray();

            return (new CustomJsonResponse($tokenData))->get();
        }

        return (new CustomErrorJsonResponse(
                Response::$statusTexts[Response::HTTP_UNAUTHORIZED], 
                Response::HTTP_UNAUTHORIZED)
            )->get();
    }
}