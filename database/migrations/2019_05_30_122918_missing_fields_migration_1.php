<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MissingFieldsMigration1 extends Migration
{
    /**
     * Run the migrations.
     * 
     * @todo DELETE MIGRATION.ADDED IN MAIN MIGRATION
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->tinyInteger('availability_step')->default(0)->after('status');
            $table->tinyInteger('basic_details_step')->default(0)->after('availability_step');
            $table->tinyInteger('review_payment_step')->default(0)->after('basic_details_step');
            $table->float('total_days')->default(0)->after('checkout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('availability_step');
            $table->dropColumn('basic_details_step');
            $table->dropColumn('review_payment_step');
            $table->dropColumn('total_days');
        });
    }
}
