<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        // 'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->email)->plainTextToken;
});

Broadcast::channel('presence-my-channel', function ($user) {
  if ($user->id) {
    return array('name' => $user->name);
  }
});

Route::middleware('auth:sanctum')->post('/pusher/auth', function (Request $request) {
	if($user = $request->user()){
		$jsonUserData = json_encode([
			'name' => $user->name,
			'user_id' => $user->id
		]);

		$string = $request->socket_id . ":" . $request->channel_name . ":" . $jsonUserData;
		$secret = env("PUSHER_APP_SECRET");
  		$sig = hash_hmac('sha256', $string, $secret);
  		$authString = env("PUSHER_APP_KEY") .":".  $sig;
  		$response = [
  			"auth"=> $authString,
  			"channel_data" => $jsonUserData
  		];
  		return json_encode($response);
	  	
	} 
	return 1;
    // return $request->user();
});

