<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/user-registration', [UserController::class, 'userregistration']);
Route::post('/user-login', [UserController::class, 'userlogin']);
Route::post('/send-otp-code', [UserController::class, 'sendotpcode']);
Route::post('/Otp-verify', [UserController::class, 'Otpverify']);
Route::post('/Set-Password', [UserController::class, 'SetPassword'])
->middleware([TokenVerificationMiddleware::class]);
