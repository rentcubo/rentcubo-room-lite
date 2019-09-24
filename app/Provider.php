<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Setting;

use App\Helpers\Helper;

use DB;

class Provider extends Authenticatable
{

    use Notifiable;

    protected $guard = 'provider';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
        
	/**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonResponse($query) {

        return $query->select(
            'providers.id as provider_id',
            'providers.username as username',
            'providers.name',
            'providers.email as email',
            'providers.picture as picture',
            'providers.mobile as mobile',
            'providers.description as description',
            'providers.token as token',
            'providers.token_expiry as token_expiry',
            'providers.social_unique_id as social_unique_id',
            'providers.login_by as login_by',
            'providers.payment_mode',
            'providers.provider_card_id',
            'providers.status as provider_status',
            'providers.email_notification_status',
            'providers.push_notification_status',
            'providers.is_verified',
            'providers.provider_type',
            'providers.registration_steps',
            'providers.created_at',
            'providers.updated_at'
            );
    
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullResponse($query) {

        return $query->select(
            'providers.id as provider_id',
            'providers.username as username',
            'providers.name',
            'providers.name as provider_name',
            'providers.email as email',
            'providers.picture as picture',
            'providers.mobile as mobile',
            'providers.description as description',
            'providers.full_address as full_address',
            'providers.work',
            'providers.school',
            'providers.languages',
            'providers.response_rate',
            'providers.token as token',
            'providers.social_unique_id as social_unique_id',
            'providers.login_by as login_by',
            'providers.payment_mode',
            'providers.provider_card_id',
            'providers.status as provider_status',
            'providers.email_notification_status',
            'providers.push_notification_status',
            'providers.is_verified',
            'providers.provider_type',
            'providers.registration_steps',
            DB::raw("DATE_FORMAT(providers.created_at, '%M %Y') as joined") ,
            'providers.created_at',
            'providers.updated_at'
            );
    
    }

    /**
     * Get the hosts record associated with the provider.
     */
    public function hosts() {

        return $this->hasMany(Host::class);

    }

    /**
     * Get the Booking Payments record associated with provider.
     */
    public function bookingPayments() {
        
        return $this->hasMany(BookingPayment::class);
    }


    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['first_name'] = $model->attributes['last_name'] = $model->attributes['name'];

            $model->attributes['is_verified'] = PROVIDER_EMAIL_VERIFIED;

            $model->attributes['status'] = PROVIDER_PENDING;

            $model->attributes['payment_mode'] = COD;

            $model->attributes['username'] = routefreestring($model->attributes['name']);

            $model->attributes['unique_id'] = uniqid();

            if(in_array($model->login_by, ['facebook' , 'google'])) {
                
                $model->attributes['password'] = \Hash::make($model->attributes['social_unique_id']);
            }

        });

        static::created(function($model) {

            $model->attributes['email_notification_status'] = $model->attributes['push_notification_status'] = YES;

            $model->attributes['unique_id'] = "UID"."-".$model->attributes['id']."-".uniqid();

            $model->attributes['token'] = Helper::generate_token();

            $model->attributes['token_expiry'] = Helper::generate_token_expiry();

            $model->save();
       
        });

        static::updating(function($model) {

            $model->attributes['username'] = routefreestring($model->attributes['name']);

            $model->attributes['first_name'] = $model->attributes['last_name'] = $model->attributes['name'];

        });

        static::deleting(function ($model){

            Helper::delete_file($model->picture , PROFILE_PATH_PROVIDER);

            $model->hosts()->delete();

        });

    }

    /**
     * Generates Token and Token Expiry
     * 
     * @return bool returns true if successful. false on failure.
     */

    protected function generateEmailCode() {

        $this->attributes['verification_code'] = Helper::generate_email_code();

        $this->attributes['verification_code_expiry'] = Helper::generate_email_expiry();

        $this->attributes['is_verified'] = 1;

        return true;
    
    }
}
