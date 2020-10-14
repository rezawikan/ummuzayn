<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\LoginCustomerResource;
use App\Http\Requests\AuthCustomer\ForgotRequest;
use App\Http\Requests\AuthCustomer\ConfirmPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\Traits\CurrentAuth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerAuthController extends Controller
{
    use CurrentAuth;

    /**
     * Handle a login for customer.
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

        return (new LoginCustomerResource($request->user()))
              ->additional([
                  'meta' => [
                    'token' => $token
                  ]
              ]);
    }

    /**
    * Handle a registration request for the customer.
    * @param  \App\Http\Requests\Auth\RegisterRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        return $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'customer_type_id' => $request->customer_type_id,
            'password' => Hash::make($request->password)
        ]);
    }

    /**
     * Handle a send link for customer.
     * @param \App\Http\Requests\AuthCustomer\ForgotRequest $request
     * @return \Illuminate\Http\Response
     */
    public function forgot(ForgotRequest $request)
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        $status = Password::RESET_LINK_SENT == $response ? true : false;

        return response()->json([
            'message' => __($response),
            'status' => $status
        ], 200);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('customers');
    }

    /**
     * Reset the password confirmation timeout.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function reset(ConfirmPasswordRequest $request)
    {
        $reset = $this->broker()->reset(
            $request->all(),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
    
                $user->setRememberToken(Str::random(60));
    
                event(new PasswordReset($user));
            }
        );

        $status = $reset == Password::PASSWORD_RESET ? true : false;

        return response()->json([
            'message' => __($reset),
            'status' => $status
        ], 200);
    }

    /**
    * Handle an update request for the customer.
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Models\Customer $customer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Customer $customer)
    {
        $customer->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'user'
          ])
        ], 200);
    }
}
