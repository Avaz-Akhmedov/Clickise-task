<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/settings', [SettingController::class, 'index']);

    Route::post('/settings/request-update', [SettingController::class, 'requestUpdate']);
    Route::post('/settings/confirm-update', [SettingController::class, 'confirmUpdate']);

});
