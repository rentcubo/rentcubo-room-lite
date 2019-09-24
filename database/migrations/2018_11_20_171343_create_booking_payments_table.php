<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('user_id');
            $table->integer('provider_id');
            $table->integer('host_id');
            $table->string('payment_id');
            $table->string('payment_mode')->default('cod');
            $table->string('currency')->default("$");
            $table->string('total_time')->default(0);
            $table->float('base_price')->default(0.00);
            $table->float('time_price')->default(0.00);
            $table->float('other_price')->default(0.00);
            $table->float('sub_total')->default(0.00);
            $table->float('tax_price')->default(0.00);
            $table->float('actual_total')->default(0.00);
            $table->float('total')->default(0.00);
            $table->float('paid_amount')->default(0.00);
            $table->dateTime('paid_date')->nullable();
            $table->float('admin_amount')->default(0.00);
            $table->float('provider_amount')->default(0.00);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('booking_payments');
    }
}
