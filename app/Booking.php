<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Booking extends Model
{

    public function getUniqueIdAttribute($value)
    {
        return "B-".$this->attributes['id'].$value;
    }

	/**
     * Get the user details associated with booking
     */
	public function userDetails() {

        return $this->belongsTo(User::class, 'user_id')->withDefault(['name' => "NO USER"]);

	}

	/**
     * Get the provider details associated with booking
     */

	public function providerDetails() {

		return $this->belongsTo(Provider::class, 'provider_id');

	}

	/**
     * Get the host details associated with booking
     */
	public function hostDetails() {

        return $this->belongsTo(Host::class, 'host_id');

	}

	/**
     * Get the booking chat details associated with booking
     */

	public function bookingChats() {

		return $this->hasMany(BookingChat::class);

	}

	/**
     * Get the booking payments details associated with booking
     */

	public function bookingPayments() {

		return $this->hasOne(BookingPayment::class, 'booking_id')->withDefault();

	}

	/**
     * Get the booking provider reviews associated with booking
     */

	public function bookingProviderReviews() {

		return $this->hasMany(BookingProviderReview::class);

	}

	/**
     * Get the booking user reviews associated with booking
     */

	public function bookingUserReviews() {

		return $this->hasMany(BookingUserReview::class);

	}

	/**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonResponse($query) {

        return $query->leftJoin('hosts', 'hosts.id', '=', 'bookings.host_id')
                    ->select(
                    	'bookings.id as booking_id', 
                    	'bookings.user_id', 
                    	'hosts.provider_id', 
                        'hosts.id as host_id', 
                    	'hosts.host_name', 'hosts.picture as host_picture', 
                    	'hosts.host_type', 'hosts.city as host_location', 
                    	 DB::raw("DATE_FORMAT(bookings.checkin, '%d %b %Y') as checkin") ,
                         DB::raw("DATE_FORMAT(bookings.checkout, '%d %b %Y') as checkout") ,
                    	'bookings.total_guests',
                    	'bookings.total_days',
                    	'bookings.total',
                    	'bookings.status'
                    );
    
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProviderBookingViewResponse($query) {

        return $query->leftJoin('hosts', 'hosts.id', '=', 'bookings.host_id')
                    ->select(
                        'bookings.id as booking_id', 
                        'bookings.unique_id as booking_unique_id', 
                        'bookings.user_id',
                        'hosts.provider_id', 
                        'bookings.description',
                        'adults', 'children', 'infants',
                        'hosts.id as host_id','hosts.host_name', 
                        'hosts.picture as host_picture', 
                        'hosts.host_type', 'hosts.city as host_location', 
                        'hosts.description as host_description',
                        'bookings.total_guests',
                         DB::raw("DATE_FORMAT(bookings.checkin, '%d %b %Y') as checkin") ,
                         DB::raw("DATE_FORMAT(bookings.checkout, '%d %b %Y') as checkout") ,
                        'bookings.total_days',
                        'bookings.per_day',
                        'bookings.currency',
                        'bookings.payment_mode',
                        'bookings.total',
                        'bookings.status',
                        DB::raw("DATE_FORMAT(bookings.cancelled_date, '%d %b %Y') as cancelled_date") ,
                        'cancelled_reason',
                        'bookings.created_at',
                        'bookings.updated_at', // @todo check and remove the values
                        DB::raw("DATE_FORMAT(bookings.created_at, '%d %b %Y') as created"),
                        DB::raw("DATE_FORMAT(bookings.updated_at, '%d %b %Y') as updated")
                    );
    
    }

    public static function boot() {

        parent::boot();


        static::creating(function ($model) {

            $unique_id = strtotime(date('Y-m-d'));

            if(isset($model->attributes['id'])) {

                $unique_id = $model->attributes['id'] ?: uniqid()."-".strtotime(date('Y-m-d H:i:s'));
            }

            $model->attributes['unique_id'] = routefreestring($unique_id);

        });
        	    
	    static::deleting(function ($model){

            $model->hostDetails()->delete();

            $model->bookingChats()->delete();
            
            $model->bookingPayments()->delete();

            $model->bookingProviderReviews()->delete();

            $model->bookingUserReviews()->delete();

	    });

	}

}
