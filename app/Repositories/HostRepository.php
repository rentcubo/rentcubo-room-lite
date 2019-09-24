<?php

namespace App\Repositories;

use App\Helpers\Helper;

use App\Helpers\HostHelper;

use DB, Log, Validator, Exception, Setting;

use App\User;

use App\Category, App\SubCategory;

use App\Host, App\HostGallery;

class HostRepository {

    /**
     *
     * @method host_list_response()
     *
     * @uses used to get the common list details for hosts
     *
     * @created Vidhya R
     *
     * @updated Vidhya R
     *
     * @param array $host_ids
     *
     * @param integer $user_id
     *
     * @return
     */

    public static function host_list_response($host_ids, $user_id) {

        $hosts = Host::whereIn('hosts.id' , $host_ids)
                            ->orderBy('hosts.updated_at' , 'desc')
                            ->UserBaseResponse()
                            ->get();

        foreach ($hosts as $key => $host_details) {

            $host_details->base_price_formatted = formatted_amount($host_details->base_price);
            
            $host_details->per_day_formatted = formatted_amount($host_details->per_day);

            $host_details->per_day_symbol = tr('list_per_day_symbol');

            $host_galleries = HostGallery::where('host_id', $host_details->host_id)->select('picture', 'caption')->skip(0)->take(3)->get();

            $host_details->gallery = $host_galleries;
        }

        return $hosts;

    } 

    /**
     *
     * @method provider_hosts_response()
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

    public static function provider_hosts_response($host_ids) {

        $hosts = Host::whereIn('hosts.id' , $host_ids)
                        ->select('hosts.id as host_id', 'hosts.host_name', 'hosts.picture as host_picture', 'hosts.host_type', 'hosts.city as host_location', 'hosts.created_at', 'hosts.updated_at')
                        ->orderBy('hosts.updated_at' , 'desc')
                        ->get();
        return $hosts;

    } 

    /**
     *
     * @method host_gallery_upload()
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

    public static function host_gallery_upload($files, $host_id, $status = YES) {

        $allowedfileExtension=['jpeg','jpg','png'];

        $host = Host::find($host_id);

        $is_host_image = $host ? ($host->picture ? YES : NO): NO;

        $data = [];

        if(!is_array($files)) {
            
            $file = $files;

            $host_gallery_details = new HostGallery;

            $host_gallery_details->host_id = $host_id;

            $host_gallery_details->picture = Helper::upload_file($file, FILE_PATH_HOST);

            $host_gallery_details->status = $status;

            $host_gallery_details->save();

            if($is_host_image == NO && $host) {

                $host->picture = $host_gallery_details->picture;

                $host->save();
            }

            $gallery_data = [];

            $gallery_data['host_gallery_id'] = $host_gallery_details->id;

            $gallery_data['file'] = $host_gallery_details->picture;

            array_push($data, $gallery_data);

            return $data;
        }


        foreach($files as $file) {

            $filename = $file->getClientOriginalName();

            $extension = $file->getClientOriginalExtension();

            $check_picture = in_array($extension, $allowedfileExtension);
            
            if($check_picture) {

                $host_gallery_details = new HostGallery;

                $host_gallery_details->host_id = $host_id;

                $host_gallery_details->picture = Helper::upload_file($file, FILE_PATH_HOST);

                $host_gallery_details->status = $status;

                $host_gallery_details->save();

                if($is_host_image == NO && $host) {

                    $host->picture = $host_gallery_details->picture;

                    $host->save();
               
                }

                $gallery_data = [];

                $gallery_data['host_gallery_id'] = $host_gallery_details->id;

                $gallery_data['file'] = $host_gallery_details->picture;

                array_push($data, $gallery_data);

           }
        
        }

        return $data;
    
    }

    /**
     *
     * @method hosts_save()
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

    public static function hosts_save($request) {

        try {
            
            $host_id = $request->host_id;

            if($host_id) {

                $host = Host::find($host_id);

                if(!$host) {
                    throw new Exception( Helper::error_message(200), 200);
                }

            } else {

                $host = new Host;

                $host->provider_id = $request->id;

                $host->save();

            
            }


            $host->category_id = $request->category_id ?: ($host->category_id ?: 0);

            $host->sub_category_id = $request->sub_category_id ?: ($host->sub_category_id ?: 0);

            $host->host_type = $request->host_type ?: ($host->host_type ?: "");

            $host->host_name = $request->host_name ?: ($host->host_name ?: "");

            $host->description = $request->description ?: $host->description;

            $host->save();

            /***** Host pictures upload ****/

            if($request->hasfile('picture')) {

                self::host_gallery_upload($request->file('picture'), $host->id);
            
            }

            /***** Host pictures upload ****/

            // Step2

            $host->street_details = $request->street_details ?: ($host->street_details ?: "");

            $host->country = $request->country ?: $host->country;

            $host->city = $request->city ?: ($host->city ?: "");

            $host->state = $request->state ?: ($host->state ?: "");

            $host->latitude = $request->latitude ?: ($host->latitude ?: 0.00);

            $host->longitude = $request->longitude ?: ($host->longitude ?: 0.00);

            $host->full_address = $request->full_address ?: ($host->full_address ?: "");

            $host->zipcode = $request->zipcode ?: ($host->zipcode ?: "");

            $host->service_location_id = $request->service_location_id ?: ($host->service_location_id ?: 0);

            $host->save();

            // Step 3 - Update Amenties details

            if($request->step == HOST_STEP_3) {

                $amenties = array_search_partial($request->all(), 'amenties_');

                foreach ((array) $amenties as $amenties_key => $amenties_value) {

                    // Check the already exists

                    $check_host_amenties = HostQuestionAnswer::where('host_id', $host->id)->where('common_question_id', $amenties_key)->first();

                    if(!$check_host_amenties) {

                        $host_amenties = new HostQuestionAnswer;

                        $host_amenties->provider_id = $request->id;

                        $host_amenties->host_id = $host->id;

                        $host_amenties->common_question_id = $amenties_key;

                        $host_amenties->common_question_answer_id = $amenties_value;

                        $host_amenties->save();

                    } else {

                        $check_host_amenties->common_question_answer_id = $amenties_value;

                        $check_host_amenties->save();
                    }

                }
            }

            // Step 5 & 6

            $host->checkin = $request->checkin ?: ($host->checkin ?: "");

            $host->checkout = $request->checkout ?: ($host->checkout ?: "");

            $host->min_days = $request->min_days ?: ($host->min_days ?: 0);

            $host->max_days = $request->max_days ?: ($host->max_days ?: 0);

            $host->base_price = $request->base_price ?: ($host->base_price ?: 0);

            $host->per_day = $request->per_day ?: ($host->per_day ?: 0);

            $host->per_week = $request->per_week ?: ($host->per_week ?: 0);

            $host->per_month = $request->per_month ?: ($host->per_month ?: 0);

            $host->cleaning_fee = $request->cleaning_fee ?: ($host->cleaning_fee ?: 0);

            $host->save();

            $response_array = ['success' => true, 'host' => $host];

            return $response_array;

        } catch(Exception $e) {

            $response_array = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return $response_array;
        }
    
    }

    /**
     * @method bookings_payment_by_stripe
     *
     * @uses stripe payment for booking
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param
     *
     * @return
     */
    
    public function bookings_payment_by_stripe($request, $booking_details) {
        return true;
    }

}