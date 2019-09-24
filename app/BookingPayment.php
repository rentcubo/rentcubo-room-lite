<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{

    /**
     * Get the user details associated with booking
     */
    public function userDetails() {

        return $this->belongsTo(User::class, 'user_id');

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
     * Get the host details associated with booking
     */
    public function bookingDetails() {

        return $this->belongsTo(Booking::class, 'booking_id');

    }  

    public function scopeBookingpaymentdetails($query){

    	return $query->leftjoin('bookings','bookings.id','=','booking_payments.booking_id')
            ->leftjoin('users','users.id','=','booking_payments.user_id')
            ->leftjoin('providers','providers.id','=','booking_payments.provider_id')
            ->leftjoin('hosts','hosts.id','=','booking_payments.host_id')
            ->select('booking_payments.*',
                'users.id as user_id','users.name as user_name',
                'providers.id as provider_id','providers.name as provider_name',
                'hosts.id as host_id','hosts.host_name as host_name')
            ->orderby('booking_payments.updated_at','desc' );
    }

    public function scopeBookingpaymentdetailsview($query){

        return $query->leftjoin('bookings','bookings.id','=','booking_payments.booking_id')
            ->leftjoin('users','users.id','=','booking_payments.user_id')
            ->leftjoin('providers','providers.id','=','booking_payments.provider_id')
            ->leftjoin('hosts','hosts.id','=','booking_payments.host_id')
            ->select('booking_payments.*',
                'users.id as user_id','users.name as user_name',
                'providers.id as provider_id','providers.name as provider_name',
                'hosts.id as host_id','hosts.host_name as host_name','hosts.description as host_description')
            ->orderby('booking_payments.updated_at','desc' );
    } 
}
