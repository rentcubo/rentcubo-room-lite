<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingUserReview extends Model
{
    public function scopeCommonResponse($query) {

    	// @todo date format

    	$query = $query->leftJoin('users', 'users.id', '=', 'booking_user_reviews.user_id')
						->leftJoin('providers', 'providers.id', '=', 'booking_user_reviews.provider_id')
						->leftJoin('hosts', 'hosts.id', '=', 'booking_user_reviews.host_id')
						->select('booking_user_reviews.id as booking_user_review_id',
						 'hosts.host_name as host_name',
						 'user_id',
			             \DB::raw('IFNULL(users.name,"") as user_name'),
			             \DB::raw('IFNULL(users.picture,"") as user_picture'),
			             'providers.id as provider_id',
			             \DB::raw('IFNULL(providers.name,"") as provider_name'),
			             \DB::raw('IFNULL(providers.picture,"") as provider_picture'),
						 'ratings', 
						 'review', 
						 'booking_user_reviews.created_at',
						 DB::raw("DATE_FORMAT(booking_user_reviews.created_at, '%d %b %Y') as created"),
                    	 DB::raw("DATE_FORMAT(booking_user_reviews.updated_at, '%d %b %Y') as updated")
						);

    	return $query;
    }

    /**
     * Get the user details associated with booking
     */
	public function userDetails() {

        return $this->belongsTo(User::class, 'user_id')->withDefault(['name' => "NO USER"]);

	}
}
