<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\LoginAdminResource;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\CurrentAuth;

class AuthController extends Controller
{
    use CurrentAuth;
    
    /**
     * Handle a login for the admin.
     * @param \Illuminate\Http\Request $request
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

        return (new LoginAdminResource($request->user()))
              ->additional([
                  'meta' => [
                    'token' => $token
                  ]
              ]);
    }

    /**
    * Handle a registration request for the admin.
    *
    * @param  \App\Http\Requests\Auth\RegisterRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function register(RegisterRequest $request)
    {
        return $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    /**
    * Handle an update request for the admin.
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Models\Admin $admin
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Admin $admin)
    {
        $admin->update(
            $request->all()
        );

        return response()->json([
          'data' => new LoginAdminResource($admin)
        ], 200);
    }
}
