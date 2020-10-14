<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\LoginCustomerResource;
use App\Http\Resources\API\LoginAdminResource;

trait CurrentAuth
{
    /**
    * logout the currenct user
    *
    * @return \Illuminate\Http\Response
    */
    public function logout()
    {
        auth()->logout();
    }

    /**
    * Show a current user.
    *
    * @return \Illuminate\Http\Response
    */
    public function me()
    {
        if (auth()->guard('customers')->check()) {
            return new LoginCustomerResource(auth()->user());
        }

        return new LoginAdminResource(auth()->user());
    }
}
