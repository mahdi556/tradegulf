<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login']);

Route::get('/email/verify', function () {
    return response()->json('verify Email', 402);
})->middleware('auth:sanctum')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     return redirect()->away('http://localhost:3000');
//     $request->fulfill();
//     // return redirect('http://localhost:8000');
// })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify'); 

Route::get('/email/verify/{id}/{hash}',[VerifyEmailController::class,'verify'] )->middleware(['signed'])->name('verification.verify');
