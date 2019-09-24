<?php

use App\Helpers\Helper;

use Carbon\Carbon;

use App\User;

use App\MobileRegister;

use App\PageCounter;

use App\Settings;

use App\Host;

use App\BookingPayment, App\BookingUserReview, App\BookingProviderReview;

/**
 * @method tr()
 *
 * @uses used to convert the string to language based string
 *
 * @created Vidhya R
 *
 * @updated
 *
 * @param string $key
 *
 * @return string value
 */
function tr($key , $other_key = "" , $lang_path = "messages.") {

    if (!\Session::has('locale')) {

        $locale = \Session::put('locale', config('app.locale'));

    }else {

        $locale = \Session::get('locale');

    }
    return \Lang::choice('messages.'.$key, 0, Array('other_key' => $other_key), $locale);
}
/**
 * @method envfile()
 *
 * @uses get the configuration value from .env file 
 *
 * @created Vidhya R
 *
 * @updated
 *
 * @param string $key
 *
 * @return string value
 */

function envfile($key) {

    $data = getEnvValues();

    if($data) {
        return $data[$key];
    }

    return "";

}


/**
 * @uses convertMegaBytes()
 * Convert bytes into mega bytes
 *
 * @return number
 */
function convertMegaBytes($bytes) {
    return number_format($bytes / 1048576, 2);
}


/**
 * Check the default subscription is enabled by admin
 *
 */

function user_type_check($user) {

    $user = User::find($user);

    if($user) {

        // if(Setting::get('is_default_paid_user') == 1) {

        //     $user->user_type = 1;

        // } else {

            // User need subscripe the plan

            // if(Setting::get('is_subscription')) {

            //     $user->user_type = 1;

            // } else {
                // Enable the user as paid user
                $user->user_type = 0;
            // }

        // }

        $user->save();

    }

}


function getEnvValues() {

    $data =  [];

    $path = base_path('.env');

    if(file_exists($path)) {

        $values = file_get_contents($path);

        $values = explode("\n", $values);

        foreach ($values as $key => $value) {

            $var = explode('=',$value);

            if(count($var) == 2 ) {
                if($var[0] != "")
                    $data[$var[0]] = $var[1] ? $var[1] : null;
            } else if(count($var) > 2 ) {
                $keyvalue = "";
                foreach ($var as $i => $imp) {
                    if ($i != 0) {
                        $keyvalue = ($keyvalue) ? $keyvalue.'='.$imp : $imp;
                    }
                }
                $data[$var[0]] = $var[1] ? $keyvalue : null;
            }else {
                if($var[0] != "")
                    $data[$var[0]] = null;
            }
        }

        array_filter($data);
    
    }

    return $data;

}

/**
 * @method register_mobile()
 *
 * @uses Update the user register device details 
 *
 * @created Vidhya R
 *
 * @updated
 *
 * @param string $device_type
 *
 * @return - 
 */

function register_mobile($device_type) {

    if($reg = MobileRegister::where('type' , $device_type)->first()) {

        $reg->count = $reg->count + 1;

        $reg->save();
    }
    
}

/**
 * @uses subtract_count()
 *
 * @uses While Delete user, subtract the count from mobile register table based on the device type
 *
 * @created vithya R
 *
 * @updated vithya R
 *
 * @param string $device_ype : Device Type (Andriod,web or IOS)
 * 
 * @return boolean
 */

function subtract_count($device_type) {

    if($reg = MobileRegister::where('type' , $device_type)->first()) {

        $reg->count = $reg->count - 1;
        
        $reg->save();
    }

}

/**
 * @method get_register_count()
 *
 * @uses Get no of register counts based on the devices (web, android and iOS)
 *
 * @created Vidhya R
 *
 * @updated
 *
 * @param - 
 *
 * @return array value
 */

function get_register_count() {

    $ios_count = MobileRegister::where('type' , 'ios')->get()->count();

    $android_count = MobileRegister::where('type' , 'android')->get()->count();

    $web_count = MobileRegister::where('type' , 'web')->get()->count();

    $total = $ios_count + $android_count + $web_count;

    return array('total' => $total , 'ios' => $ios_count , 'android' => $android_count , 'web' => $web_count);

}

/**
 * @method: last_x_days_page_view()
 *
 * @uses: to get last x days page visitors analytics
 *
 * @created Anjana H
 *
 * @updated Anjana H
 *
 * @param - 
 *
 * @return array value
 */
function last_x_days_page_view($days) {

    $views = PageCounter::orderBy('created_at','asc')->where('created_at', '>', Carbon::now()->subDays($days))->where('page','home');
 
    $arr = array();
 
    $arr['count'] = $views->count();

    $arr['get'] = $views->get();

      return $arr;
}

/**
 * @method last_x_days_revenue()
 *
 * @uses to get revenue analytics 
 *
 * @created Anjana H
 * 
 * @updated Anjana H
 * 
 * @param  integer $days
 * 
 * @return array of revenue totals
 */
function last_x_days_revenue($days) {
            
    $data = new \stdClass;

    $data->currency = $currency = Setting::get('currency', '$');

    // Last 10 days revenues

    $last_x_days_revenues = [];

    $start  = new \DateTime('-10 days', new \DateTimeZone('UTC'));

    $period = new \DatePeriod($start, new \DateInterval('P1D'), $days);

    $dates = $last_x_days_revenues = [];

    foreach ($period as $date) {

        $current_date = $date->format('Y-m-d');

        $last_x_days_data = new \stdClass;

        $last_x_days_data->date = $current_date;
      
        $last_x_days_total_booking_earnings = BookingPayment::whereDate('paid_date', '=', $current_date)->where('status' , DEFAULT_TRUE)->sum('paid_amount');
      
        $last_x_days_data->total_earnings = $last_x_days_total_booking_earnings ?: 0.00;

        array_push($last_x_days_revenues, $last_x_days_data);

    }
    
    $data->last_x_days_revenues = $last_x_days_revenues;

    return $data;

}

/**
 * @method: get_hosts_count()
 *
 * @uses: to get host analytics as verified,unverified,total counts
 *
 * @created Anjana H
 *
 * @updated Anjana H
 *
 * @param - 
 *
 * @return array value
 */
function get_hosts_count() {

    $verified_count = Host::where('is_admin_verified' , ADMIN_HOST_APPROVED)->get()->count();

    $unverified_count = Host::where('is_admin_verified' , ADMIN_HOST_PENDING)->get()->count();
    
    $total = $verified_count + $unverified_count;

    return array('total' => $total , 'verified_count' => $verified_count , 'unverified_count' => $unverified_count);
}

function counter($page){

    $count_home = PageCounter::wherePage($page)->where('created_at', '>=', new DateTime('today'));

        if($count_home->count() > 0) {
            $update_count = $count_home->first();
            $update_count->unique_id = uniqid();
            $update_count->count = $update_count->count + 1;
            $update_count->save();
        } else {
            $create_count = new PageCounter;
            $create_count->page = $page;
            $create_count->unique_id = uniqid();
            $create_count->count = 1;
            $create_count->save();
        }

}

/**
 * @uses this function convert string to UTC time zone
 */

function convertTimeToUTCzone($str, $userTimezone, $format = 'Y-m-d H:i:s') {

    $new_str = new DateTime($str, new DateTimeZone($userTimezone));

    $new_str->setTimeZone(new DateTimeZone('UTC'));

    return $new_str->format( $format);
}

/**
 * @uses this function converts string from UTC time zone to current user timezone
 */

function convertTimeToUSERzone($str, $userTimezone, $format = 'Y-m-d H:i:s') {

    if(empty($str)) {

        return '';
    }
    
    try {
        
        $new_str = new DateTime($str, new DateTimeZone('UTC') );
        
        $new_str->setTimeZone(new DateTimeZone( $userTimezone ));
    }
    catch(\Exception $e) {
        // Do Nothing

        return '';
    }
    
    return $new_str->format( $format);

}

function number_format_short( $n, $precision = 1 ) {

    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;

}

function common_date($date , $timezone = "" , $format = "d M Y h:i A") {

    if($date == "0000-00-00 00:00:00" || $date == "0000-00-00" || !$date) {

        return $date;
    }

    if($timezone) {

        $date = convertTimeToUSERzone($date , $timezone , $format);

    }

    return date($format , strtotime($date));

}


/**
 * @method delete_value_prefix()
 * 
 * @uses used for concat string, while deleting the records from the table
 *
 * @created vidhya R
 *
 * @updated vidhya R
 *
 * @param $prefix - from settings table (Setting::get('prefix_user_delete'))
 *
 * @param $primary_id - Primary ID of the delete record
 *
 * @param $is_email 
 *
 * @return concat string based on the input values
 */

function delete_value_prefix($prefix , $primary_id , $is_email = 0) {

    if($is_email) {

        $site_name = str_replace(' ', '_', Setting::get('site_name'));

        return $prefix.$primary_id."@".$site_name.".com";
        
    } else {
        return $prefix.$primary_id;

    }

}

/**
 * @method routefreestring()
 * 
 * @uses used for remove the route parameters from the string
 *
 * @created vidhya R
 *
 * @updated vidhya R
 *
 * @param string $string
 *
 * @return Route parameters free string
 */

function routefreestring($string) {

    $string = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string));
    
    $search = [' ', '&', '%', "?",'=','{','}','$'];

    $replace = ['-', '-', '-' , '-', '-', '-' , '-','-'];

    $string = str_replace($search, $replace, $string);

    return $string;
    
}

/**
 * @uses showEntries()
 *
 * To load the entries of the row
 *
 * @created_by Maheswari
 *
 * @updated_by Anjana
 *
 * @return reponse of serial number
 */
function showEntries($request, $i) {

    $s_no = $i;

    // Request Details + s.no

    if (isset($request['page'])) {

        $s_no = (($request['page'] * 10) - 10 ) + $i;

    }

    return $s_no;

}

function array_search_partial($listArray, $keyword) {

    $data = [];

    foreach($listArray as $index => $value) {
 
        if (strpos($index, $keyword) !== FALSE) {

            $key = str_replace('amenties_', "", $index);

            $data[$key] = $value;
        }

    }

    return $data;
}

/**
 * @method nFormatter()
 *
 * @uses used to format the number with 10k, 20M etc.
 *
 * @created vidhya R
 *
 * @updated vidhya R
 *
 * @param integer $num
 * 
 * @param string $currency
 *
 * @return string $formatted_amount
 */

function nFormatter($number, $currency = "") {

    $currency = Setting::get('currency', "$");

    if($number > 1000) {

        $x = round($number);

        $x_number_format = number_format($x);

        $x_array = explode(',', $x_number_format);

        $x_parts = ['k', 'm', 'b', 't'];

        $x_count_parts = count($x_array) - 1;

        $x_display = $x;

        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');

        $x_display .= $x_parts[$x_count_parts - 1];

        return $currency." ".$x_display;

    }

    return $currency." ".$number;

}

/**
 * @method formatted_amount()
 *
 * @uses used to format the number
 *
 * @created vidhya R
 *
 * @updated vidhya R
 *
 * @param integer $num
 * 
 * @param string $currency
 *
 * @return string $formatted_amount
 */

function formatted_amount($amount = 0.00, $currency = "") {

    $currency = $currency ?: Setting::get('currency', '$');

    $formatted_amount = $currency."".$amount;

    return $formatted_amount;
}

function generate_between_dates($start_date, $end_date = "", $format = "Y-m-d" ,$no_of_days = 1, $days_type = 'add') {

    $start_date = new Carbon(Carbon::parse($start_date)->format('Y-m-d'));

    if($end_date == "") {

        if($days_type == 'add') {

            $end_date = new Carbon(Carbon::parse()->addDay($no_of_days)->format('Y-m-d'));

        } else {
            
            $subtracted_date = new Carbon(Carbon::parse()->subDays($no_of_days)->format('Y-m-d'));

            $end_date = $start_date;

            $start_date = $subtracted_date;
        
        }

    }

    $all_dates = array();

    while ($start_date->lte($end_date)) {

      $all_dates[] = $start_date->toDateString();

      $start_date->addDay();
    }

    return $all_dates;

}

function total_days($start_date, $end_date) {

    $start_date = Carbon::parse($start_date);

    $end_date = Carbon::parse($end_date);

    $total_days = $start_date->diffInDays($end_date);

    // As per booking table adding one date

    $days = $total_days ? $total_days+1 : 0;

    Log::info("total_days".$total_days);

    return $days;
}


function booking_status($status) {

    $status_string = '';

    switch ($status) {
        case 0:
            $status_string = tr('BOOKING_INITIATE');
            break;
        case 1:
            $status_string = tr('BOOKING_ONPROGRESS');
            break;
        case 2:
            $status_string = tr('BOOKING_WAITING_FOR_PAYMENT');
            break;
        case 3:
            $status_string = tr('BOOKING_DONE_BY_USER');
            break;
        case 4:
            $status_string = tr('BOOKING_CANCELLED_BY_USER');
            break;
        case 5:
            $status_string = tr('BOOKING_CANCELLED_BY_PROVIDER');
            break;
        case 6:
            $status_string = tr('BOOKING_COMPLETED');
            break;
        case 7:
            $status_string = tr('BOOKING_REFUND_INITIATED');
            break;
        case 8:
            $status_string = tr('BOOKING_CHECKIN');
            break;
        case 9:
            $status_string = tr('BOOKING_CHECKOUT');
            break;
        
        default:
            # code...
            break;
    }

    return $status_string;

}

function booking_btn_status($booking_status, $booking_id, $type = USER) {

    $buttons = new \stdClass;

    $buttons->cancel_btn_status = $buttons->review_btn_status = $buttons->checkin_btn_status = $buttons->checkout_btn_status = $buttons->message_btn_status = NO;

    $buttons->is_checkedin = NO;

    if(in_array($booking_status, [BOOKING_INITIATE, BOOKING_ONPROGRESS, BOOKING_WAITING_FOR_PAYMENT, BOOKING_DONE_BY_USER])) {

        $buttons->cancel_btn_status = $buttons->message_btn_status = YES;
    }

    if(in_array($booking_status, [BOOKING_DONE_BY_USER])) {
        
        $buttons->checkin_btn_status = YES;

    }

    if($booking_status == BOOKING_CHECKIN) {

        $buttons->checkout_btn_status = $buttons->is_checkedin = YES;

    }

    if($booking_status == BOOKING_CHECKOUT) {

        $buttons->is_checkedin = 2; // Checkout

        $review_count = 0;

        if($type == USER) {

            $review_count = BookingUserReview::where('booking_id', $booking_id)->count();

        } else {
            $review_count = BookingProviderReview::where('booking_id', $booking_id)->count();
        }


        $buttons->review_btn_status = $review_count == 0 ? YES : BOOKING_REVIEW_DONE;

    }

    return $buttons;

}
