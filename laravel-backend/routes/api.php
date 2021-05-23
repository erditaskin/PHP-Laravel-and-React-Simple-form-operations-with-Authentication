<?php

use App\Http\Controllers\AuctionAutoBidController;
use App\Http\Controllers\AuctionBidController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth.guard')->group(function () {
    Route::get('/user-settings', [UserSettingController::class, 'userSettings']);
    Route::post('/user-settings/update', [UserSettingController::class, 'userSettingsUpdate']);
    Route::prefix('/auction')->group(function () {
        Route::get('/list', [AuctionController::class, 'auctionList']);
        Route::get('/detail/{auction}', [AuctionController::class, 'auctionDetail']);
        Route::post('/bid/{auction}', [AuctionBidController::class, 'applyBid']);
        Route::post('/auto-bid/{auction}', [AuctionAutoBidController::class, 'switchAutoBidMode']);
    });
});
