<?php 

namespace App\Helpers;

use Hash, Exception, Log, Setting, DB;

use App\Repositories\BookingRepository as BookingRepo;

use App\Repositories\HostRepository as HostRepo;

use App\Admin, App\User, App\Provider;

use App\Host;

use Carbon\Carbon;

use Carbon\CarbonPeriod;

use App\SubCategory;

use App\Category;


class HostHelper {

    /** 
     * @method check_valid_dates()
     *
     * @created vithya R
     *
     * @updated vithya R
     *
     * @param integer $host_id 
     * 
     * @param integer $user_id 
     *
     * @return boolean
     */
    
    public static function check_valid_dates($dates) {

        $list_dates = $dates ? explode(',', $dates) : [];

        $list_dates = array_filter($list_dates,function($date){
            return strtotime($date) > strtotime('today');
        });

        return $list_dates; 

    }

    /**
     * @method generate_date_range()
     * 
     * @uses Creating date collection between two dates
     *
     * @param string since any date, time or datetime format
     * 
     * @param string until any date, time or datetime format
     * 
     * @param string step
     * 
     * @param string date of output format
     * 
     * @return array
     */
    public static function generate_date_range($year = "", $month = "", $step = '+1 day', $output_format = 'd/m/Y', $loops = 2) {

        $year = $year ?: date('Y');

        $month = $month ?: date('m');

        $data = [];

        for($current_loop = 0; $current_loop < $loops; $current_loop++) {

            // Get the start and end date of the months

            $month_start_date = Carbon::createFromDate($year, $month, 01)->format('Y-m-d');

            $no_of_days = Carbon::parse($month_start_date)->daysInMonth;

            $month_end_date = Carbon::createFromDate($year, $month, $no_of_days)->format('Y-m-d');

            $period = CarbonPeriod::create($month_start_date, $month_end_date);

            $dates = [];

            // Iterate over the period
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }

            // Create object

            $loop_data = new \stdClass;;

            $loop_data->month = $month;

            $loop_data->year = $year;

            $loop_data->total_days = $no_of_days;

            $loop_data->dates = $dates;

            array_push($data, $loop_data);

            // Update the next loops

            if($loops > 1) {

                $check_date = Carbon::createFromDate($year, $month, 01)->addMonth(1)->day(01);

                $year = $check_date->year;

                $month = $check_date->month;
            }
        
        }

        return $data;
    }

    /**
     *
     * @method get_host_types()
     *
     * @uses get host types
     *
     * @created vithya R
     *
     * @updated vithya R
     *
     * @param
     *
     * @return 
     */

    public static function get_host_types($host_type = "") {

        $host_data[0]['title'] = tr('user_host_type_entire_place_title');
        $host_data[0]['description'] = tr('user_host_type_entire_place_description');
        $host_data[0]['search_value'] = HOST_ENTIRE;

        $host_data[1]['title'] = tr('user_host_type_private_title');
        $host_data[1]['description'] = tr('user_host_type_private_description');
        $host_data[1]['search_value'] = HOST_PRIVATE;

        $host_data[2]['title'] = tr('user_host_type_shared_title');
        $host_data[2]['description'] = tr('user_host_type_shared_description');
        $host_data[2]['search_value'] = HOST_SHARED;

        return $host_data;
    
    }
}
