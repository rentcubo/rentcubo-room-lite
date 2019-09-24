<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id');
            $table->integer('user_id');
            $table->integer('provider_id');
            $table->integer('host_id');
            $table->text('description');
            $table->integer('total_guests')->default(1);
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->string('host_checkin');
            $table->string('host_checkout');
            $table->dateTime('checkin');
            $table->dateTime('checkout');
            $table->float('per_day')->default(0.00);
            $table->string('currency')->default("$");
            $table->float('total')->default(0.00);
            $table->string('payment_mode');
            $table->tinyInteger('status')->default(0);
            $table->text('cancelled_reason');
            $table->dateTime('cancelled_date');
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
        Schema::dropIfExists('bookings');
    }
}
