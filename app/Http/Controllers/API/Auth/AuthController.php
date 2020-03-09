<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\LoginUserResource;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\API\Auth\ApiRegistered;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
            'errors' => [
              'email' => ['Could not login with those det']
              ]
            ], 401);
        }

        return (new LoginUserResource($request->user()))
              ->additional([
                'meta' => [
                  'token' => $token
                ]
              ]);
    }

    /**
    * Handle a registration request for the application.
    *
    * @param  \App\Http\Requests\Auth\RegisterRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function register(RegisterRequest $request)
    {
        event(new ApiRegistered(
            $user = User::create([
              'name' => $request->name,
              'email' => $request->email,
              'password' => Hash::make($request->password)
          ])
        ));
    }
}
