<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
    protected const AUTH_TOKEN_EXP_HOURS = '24';

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

        $tokenData = $user->createToken(
            'app', 
            User::AUTH_ABILITIES,
            Carbon::now()->addHours(self::AUTH_TOKEN_EXP_HOURS)
        )->toArray();

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
            $user = Auth::user();
            
            // revoke all issued tokens so far, only one valid at a time
            $user->tokens()->delete();

            $tokenData = $user->createToken(
                'app', 
                User::AUTH_ABILITIES,
                Carbon::now()->addHours(self::AUTH_TOKEN_EXP_HOURS)
            )->toArray();

            return (new CustomJsonResponse($tokenData))->get();
        }

        return (new CustomErrorJsonResponse(
                Response::$statusTexts[Response::HTTP_UNAUTHORIZED], 
                Response::HTTP_UNAUTHORIZED)
            )->get();
    }
    
    /**
     * @param  Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        // revoke all the issued tokens
        $user->tokens()->delete();

        return response()->json(['status_code' => Response::HTTP_OK]);
    }
}