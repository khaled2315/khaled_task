<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArticleController;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleController::class);
});

Route::post('sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = Auth::attempt($request->only('email', 'password'));

    if (!$user) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $request->user()->createToken($request->device_name);
    return ['token' => $token->plainTextToken];
});
