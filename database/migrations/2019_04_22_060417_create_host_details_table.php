<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_id');
            $table->integer('provider_id');
            $table->integer('total_guests')->default(0);
            $table->integer('min_guests')->default(0);
            $table->integer('max_guests')->default(0);
            $table->integer('total_bedrooms')->default(0);
            $table->integer('total_beds')->default(0);
            $table->integer('total_bathrooms')->default(0);
            $table->string('bathroom_type')->default('private');
            $table->tinyInteger('step1')->default(0);
            $table->tinyInteger('step2')->default(0);
            $table->tinyInteger('step3')->default(0);
            $table->tinyInteger('step4')->default(0);
            $table->tinyInteger('step5')->default(0);
            $table->tinyInteger('step6')->default(0);
            $table->tinyInteger('step7')->default(0);
            $table->tinyInteger('step8')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('host_details');
    }
}
