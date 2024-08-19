<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\OTPVerificationController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RoommateController;
use App\Http\Controllers\PgListingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdsController;

// routes/api

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [RegisterController::class, 'logout']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/get-otp', [OTPController::class, 'getOTP']);
Route::middleware('auth:sanctum')->get('/details', [RegisterController::class, 'details']);
Route::post('/verify-otp', [OTPVerificationController::class, 'verifyOtp']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [ResetPasswordController::class, 'reset']);
Route::get('/password/reset/', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('/listings', [ListingController::class, 'store']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/property/{id}/{location}/{listingType}', [PropertyController::class, 'show']);
Route::post('/upload', [ImageController::class, 'upload']);
Route::post('/roommates', [RoommateController::class,'store']);
Route::post('/pg_listings', [PgListingController::class, 'store']);
Route::get('/userDetail', [UserController::class, 'decodeToken']);
Route::get('/user-ads/{id}/', [AdsController::class, 'getUserAds']);


use App\Http\Controllers\PasswordChangeController;

Route::post('/change-password', [PasswordChangeController::class, 'changePassword']);

Route::get('user/{userId}/ads', [AdsController::class, 'getUserAds']);






