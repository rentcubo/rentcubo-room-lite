<?php

namespace App\Repositories;

use App\Helpers\Helper;

use App\Helpers\HostHelper;

use Log, Validator, Setting;

use App\User;

use App\Host, App\HostGallery;

class BookingRepository {

    /**
     *
     * @method booking_list_response()
     *
     * @uses used to get the common list details for hosts
     *
     * @created Vidhya R
     *
     * @updated Vidhya R
     *
     * @param 
     *
     * @return
     */

    public static function booking_list_response($booking_ids) {

        $bookings = Booking::whereIn('bookings.id' , $booking_ids)
                            ->leftJoin('hosts', 'hosts.id', '=', 'bookings.host_id')
                            ->select('bookings.id as booking_id', 'hosts.provider_id', 'hosts.picture as host_picture', 'hosts.host_type', 'hosts.city as host_location', 'bookings.checkin', 'bookings.checkout')
                            ->orderBy('bookings.updated_at' , 'desc')
                            ->get();
        return $bookings;

    } 

    /**
     *
     * @method check_booking_status()
     *
     * @uses used to get the common list details for hosts
     *
     * @created Vidhya R
     *
     * @updated Vidhya R
     *
     * @param 
     *
     * @return
     */
    public static function check_booking_status($booking_details) {

        // Check the checkin & checkin dates are updated

        $availability_step = $booking_details->availability_step; // checkin, checkout and total_guests

        if($booking_details->checkin && $booking_details->checkout && $booking_details->total_guests && $availability_step == NO) {
            $booking_details->availability_step = YES;
        }

        $basic_details_step = $booking_details->basic_details_step; // title, description

        if($booking_details->description && $basic_details_step == NO) {

            $booking_details->basic_details_step = YES;
    
        }

        $review_payment_step = $booking_details->review_payment_step;

        if($booking_details->payment_mode && $review_payment_step == NO) {

            $booking_details->review_payment_step = YES;

        }

        $booking_details->save();

    }	
}