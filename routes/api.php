<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::post('/cadastro',function (Request $request) {
    $cad = $request->all();
    $user = User::create([
        'name' => $cad['name'],
        'email' => $cad['email'],
        'password' => bcrypt($cad['password']),
    ]);
    $user->token = $user->createToken($user->email)->accessToken;
    
    return $user;
});

Route::middleware('auth:api')->get('/usuario', function (Request $request) {
    return $request->user();
});
