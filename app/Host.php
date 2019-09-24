<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\Helper;

class Host extends Model
{

    public function getDescriptionAttribute($value)
    {
        return rtrim($value);
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonResponse($query) {

        $currency = \Setting::get('currency' , '$');

        return $query
            // ->where('hosts.status' , HOST_OWNER_PUBLISHED)
            ->where('hosts.admin_status' , ADMIN_HOST_APPROVED)
            // ->where('hosts.is_admin_verified' , ADMIN_HOST_VERIFIED)
            ->leftJoin('providers','providers.id' ,'=' , 'hosts.provider_id')
            ->leftJoin('categories','categories.id' ,'=' , 'hosts.category_id')
            ->leftJoin('sub_categories','sub_categories.id' ,'=' , 'hosts.sub_category_id')
            ->select(
            'hosts.id as host_id',
            'hosts.host_name as host_name',
            'hosts.picture as host_picture',
            'hosts.overall_ratings',
            'hosts.total_ratings',
            'hosts.provider_id as provider_id',
             \DB::raw('IFNULL(providers.name,"") as provider_name'),
             \DB::raw('IFNULL(providers.picture,"") as provider_picture'),
            'hosts.category_id',
             \DB::raw('IFNULL(categories.name,"") as category_name'),
            'hosts.sub_category_id',
             \DB::raw('IFNULL(sub_categories.name,"") as sub_category_name'),
            'hosts.city',
            'hosts.base_price as base_price',
            \DB::raw("'$currency' as currency"),
            'hosts.created_at',
            'hosts.updated_at', // @todo check and remove the dates
            \DB::raw("DATE_FORMAT(hosts.created_at, '%M %Y') as created") ,
            \DB::raw("DATE_FORMAT(hosts.updated_at, '%M %Y') as updated") 
            );
    
    }

    public function scopeVerifedHostQuery($query) {

        return $query->where('hosts.admin_status' , ADMIN_HOST_APPROVED);
   
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserBaseResponse($query) {

        $currency = \Setting::get('currency' , '$');

        return $query->where('hosts.admin_status' , ADMIN_HOST_APPROVED)
            ->leftJoin('providers','providers.id' ,'=' , 'hosts.provider_id')
            ->leftJoin('sub_categories','sub_categories.id' ,'=' , 'hosts.sub_category_id')
            ->select(
            'hosts.id as host_id',
            'hosts.unique_id as host_unique_id',
            'hosts.host_name as host_name',
            'hosts.host_type as host_type',
            'hosts.picture as host_picture',
            'hosts.overall_ratings',
            'hosts.total_ratings',
            'hosts.provider_id as provider_id',
            'sub_categories.name as sub_category_name',
            'hosts.city as host_location',
            'hosts.latitude',
            'hosts.longitude',
            'hosts.base_price as base_price',
            'hosts.per_day as per_day',
            \DB::raw("'$currency' as currency")
            );
    
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingleBaseResponse($query) {

        $currency = \Setting::get('currency' , '$');

        return $query
            ->leftJoin('categories','categories.id' ,'=' , 'hosts.category_id')
            ->leftJoin('sub_categories','sub_categories.id' ,'=' , 'hosts.sub_category_id')
            ->leftJoin('host_details','host_details.host_id' ,'=' , 'hosts.id')
            ->select(
            'hosts.id as host_id',
            'hosts.unique_id as host_unique_id',
            'hosts.provider_id as provider_id',
            'hosts.host_name as host_name',
            'hosts.host_type as host_type',
            'hosts.description as host_description',
            'hosts.category_id as category_id',
            'categories.name as category_name',
            'hosts.sub_category_id as sub_category_id',
            'sub_categories.name as sub_category_name',
            'hosts.city as host_location',
            'hosts.picture as host_picture',
            'hosts.overall_ratings',
            'hosts.total_ratings',
            'hosts.latitude',
            'hosts.longitude',
            'hosts.checkin',
            'hosts.checkout',
            'host_details.min_guests',
            'host_details.max_guests',
            'hosts.min_days',
            'hosts.max_days',
            \DB::raw("'$currency' as currency"),
            'hosts.per_day'
            );
    
    }

    public function hostGalleries() {
        return $this->hasMany(HostGallery::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the booking payments record associated with the host.
     */
    public function bookingPayments() {
        
        return $this->hasMany(BookingPayment::class);
    }

    public function providerDetails() {
        return $this->belongsTo(Provider::class , 'provider_id');
    }

    public function categoryDetails() {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function subCategoryDetails() {
        return $this->belongsTo(SubCategory::class , 'sub_category_id');
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['unique_id'] = routefreestring(isset($model->attributes['host_name']) ? $model->attributes['host_name'] : uniqid());

        });

        static::updating(function($model) {

            $model->attributes['unique_id'] = routefreestring(isset($model->attributes['host_name']) ? $model->attributes['host_name'] : uniqid());

        });

        static::deleting(function($model) {

            foreach ($model->hostGalleries as $key => $host_gallery_details) {

                Helper::delete_file($host_gallery_details->picture , FILE_PATH_HOST);

                $host_gallery_details->delete();
            
            }

            $model->bookings()->delete();

        });
    
    }
}
