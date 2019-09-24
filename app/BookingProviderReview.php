<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingProviderReview extends Model
{
    public function scopeCommonResponse($query) {

    	// @todo date format

    	$query = $query->leftJoin('users', 'users.id', '=', 'booking_provider_reviews.user_id')
						->leftJoin('providers', 'providers.id', '=', 'booking_provider_reviews.provider_id')
						->leftJoin('hosts', 'hosts.id', '=', 'booking_provider_reviews.host_id')
						->select('booking_provider_reviews.id as booking_provider_review_id',
						 'hosts.host_name as host_name',
						 'user_id',
			             \DB::raw('IFNULL(users.name,"") as user_name'),
			             \DB::raw('IFNULL(users.picture,"") as user_picture'),
			             'providers.id as provider_id',
			             \DB::raw('IFNULL(providers.name,"") as provider_name'),
			             \DB::raw('IFNULL(providers.picture,"") as provider_picture'),
						 'ratings', 
						 'review', 
						 'booking_provider_reviews.created_at',
                    	 DB::raw("DATE_FORMAT(booking_provider_reviews.created_at, '%d %b %Y') as created"),
                    	 DB::raw("DATE_FORMAT(booking_provider_reviews.updated_at, '%d %b %Y') as updated")

						);

    	return $query;

    }
}
