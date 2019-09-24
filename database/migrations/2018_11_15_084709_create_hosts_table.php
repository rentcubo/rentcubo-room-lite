<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id');
            $table->integer('provider_id');
            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->string('host_name');
            $table->string('host_type');
            $table->text('description');
            $table->string('picture');
            $table->integer('service_location_id');
            $table->double('latitude',15,8);
            $table->double('longitude',15,8);
            $table->text('full_address')->nullable();
            $table->string('street_details')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('checkin')->nullable();
            $table->string('checkout')->nullable();
            $table->string('min_days')->default(0);
            $table->string('max_days')->default(0);
            $table->float('base_price')->default(0.00);
            $table->float('per_day')->default(0.00);
            $table->float('per_week')->default(0.00);
            $table->float('per_month')->default(0.00);
            $table->float('cleaning_fee')->default(0.00);
            $table->float('tax_price')->default(0.00);
            $table->float('overall_ratings')->default(0);
            $table->integer('total_ratings')->default(0);
            $table->tinyInteger('is_admin_verified')->default(0);
            $table->tinyInteger('admin_status')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('uploaded_by')->default(PROVIDER);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hosts');
    }
}
