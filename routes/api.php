<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
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

Route::post('/cadastro',function (Request $request) {
    $cad = $request->all();

    $validacao =  Validator::make($cad, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if($validacao->fails()){
        return $validacao->errors();
    }

    $user = User::create([
        'name' => $cad['name'],
        'email' => $cad['email'],
        'password' => bcrypt($cad['password']),
    ]);
    $user->token = $user->createToken($user->email)->accessToken;
    
    return $user;
});

Route::post('/login',function (Request $request) {
    $log = $request->all();

    $validacao =  Validator::make($log, [
      
        'email' => 'required|string|email|max:255|',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if($validacao->fails()){
        return $validacao->errors();
    }
    if(Auth::attempt(['email'=>$log['email'],'password'=>$log['password']])){
        $user = auth()->user();
        $user->token = $user->createToken($user->email)->accessToken;
        return $user;
    } else{
        return ['status' =>false];
    }
 
    
    
});


Route::middleware('auth:api')->get('/usuario', function (Request $request) {
    return $request->user();
});
