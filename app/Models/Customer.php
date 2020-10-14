<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeOrderable;
use App\Models\Traits\CanBeScoped;
use App\Models\CustomerAddress;
use Laravel\Scout\Searchable;
use App\Models\CustomerType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ForgotPasswordNotification;

class Customer extends Authenticatable implements JWTSubject
{
    use Notifiable, CanBeScoped, CanBeOrderable, Searchable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_type_id', 'name', 'email', 'phone', 'password'
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
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'customers_index';
    }

    /**
     * Get the customer type that owns the customer.
     */
    public function type()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
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
