<?php

use App\Http\Controllers\Api\FaceCustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/customers/register-face', [FaceCustomerController::class, 'store']);
Route::post('/customers/detect-member', [FaceCustomerController::class, 'detectMember']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
