<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeOrderable;
use App\Models\Traits\CanBeScoped;
use App\Models\CustomerAddress;
use App\Models\CustomerPoint;
use App\Models\CustomerType;
use App\Models\ProductVariation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ForgotPasswordNotification;

class Customer extends Authenticatable implements JWTSubject
{
    use Notifiable, CanBeScoped, CanBeOrderable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_type_id', 'customer_point_id', 'name', 'email', 'phone', 'password'
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the cart that owns the customer.
     */
    public function cart()
    {
        return $this->belongsToMany(ProductVariation::class, 'cart_customers')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Get the orders type that owns the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the customer type that owns the customer.
     */
    public function type()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

    /**
     * Get the customer point that owns the customer.
     */
    public function point()
    {
        return $this->belongsTo(CustomerPoint::class, 'customer_point_id');
    }

    /**
    * Get the customer addresses for the customer.
    */
    public function customer_addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
    * Get the default address for the customer.
    */
    public function default_address()
    {
        return $this->customer_addresses()->currentAddress()->get();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $when = now()->addSeconds(10);
        
        $this->notify(new ForgotPasswordNotification($token, 'customer'));
    }
}
