<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\Helper;

class HostGallery extends Model
{
    
    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonResponse($query) {

        return $query->select(
            'host_galleries.id as host_gallery_id',
            'host_galleries.picture'
            );
    
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

        });

        static::created(function($model) {

        });

        static::updating(function($model) {

        });

        static::deleting(function($model) {

            // Delete the picture location

            Helper::delete_file($model->picture , FILE_PATH_HOST);

        });
   
    }

}
