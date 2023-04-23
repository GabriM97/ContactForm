<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Responses\CustomJsonResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'list'])->name('contacts.list');
    Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
    Route::delete('/{contact}', [ContactController::class, 'delete'])->name('contacts.delete');
});

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/details', fn(Request $request) => (new CustomJsonResponse($request->user()))->get())->middleware('auth:api');

});