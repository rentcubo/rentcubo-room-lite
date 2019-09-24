<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Setting, DB;

use App\Helpers\Helper;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','login_by', 'device_type', 'mobile', 'picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
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
            'users.id as user_id',
            'users.username as username',
            'users.name',
            'users.email as email',
            'users.picture as picture',
            'users.description as description',
            'users.mobile as mobile',
            'users.token as token',
            'users.token_expiry as token_expiry',
            'users.social_unique_id as social_unique_id',
            'users.login_by as login_by',
            'users.payment_mode',
            'users.user_card_id',
            'users.status as user_status',
            'users.email_notification_status',
            'users.push_notification_status',
            'users.is_verified',
            'users.user_type',
            'users.registration_steps',
            'users.created_at',
            'users.updated_at'
            );
    
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOtherCommonResponse($query) {

        return $query->select(
            'users.id as user_id',
            'users.username as username',
            'users.name',
            'users.email as email',
            'users.picture as picture',
            'users.mobile as mobile',
            'users.description as description',
            'users.full_address as full_address',
            'users.work',
            'users.school',
            'users.languages',
            'users.social_unique_id as social_unique_id',
            'users.status as user_status',
            'users.is_verified',
            'users.user_type',
            DB::raw("DATE_FORMAT(users.created_at, '%M %Y') as joined") ,
            'users.created_at',
            'users.updated_at'
            );

    }

    /**
     * Get the bookings record associated with the user.
     */
    public function userBookings() {
        
        return $this->hasMany(Booking::class);
    }    

    /**
     * Get the bookings record associated with the user.
     */
    public function userBookingPayments() {
        
        return $this->hasMany(BookingPayment::class);
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['first_name'] = $model->attributes['last_name'] = $model->attributes['name'];


            $model->attributes['is_verified'] = USER_EMAIL_VERIFIED;

            $model->attributes['status'] = USER_APPROVED;

            $model->attributes['payment_mode'] = COD;

            $model->attributes['username'] = routefreestring($model->attributes['name']);

            $model->attributes['unique_id'] = uniqid();

            $model->attributes['token'] = Helper::generate_token();

            $model->attributes['token_expiry'] = Helper::generate_token_expiry();

            if(in_array($model->attributes['login_by'], ['facebook' , 'google'])) {
                
                $model->attributes['password'] = \Hash::make($model->attributes['social_unique_id']);
            }

        });

        static::created(function($model) {

            $model->attributes['email_notification_status'] = $model->attributes['push_notification_status'] = YES;

            $model->attributes['unique_id'] = "UID"."-".$model->attributes['id']."-".uniqid();

            $model->attributes['token'] = Helper::generate_token();

            $model->attributes['token_expiry'] = Helper::generate_token_expiry();

            $model->save();

            /**
             * @todo Update total number of users 
             */
        
        });

        static::updating(function($model) {

            $model->attributes['username'] = routefreestring($model->attributes['name']);

            $model->attributes['first_name'] = $model->attributes['last_name'] = $model->attributes['name'];

        });

        static::deleting(function ($model){

            Helper::delete_file($model->picture , PROFILE_PATH_USER);

            // Bookings || Cards || Wishlists 

            $model->userBookings()->delete();

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

        // Check Email verification controls and email configurations

        $this->attributes['is_verified'] = 1;

        return true;
    
    }

}
