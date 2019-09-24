<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id');
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('token');
            $table->string('provider_type')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('description')->default("");
            $table->string('mobile')->default("");
            $table->string('picture')->default(envfile('APP_URL')."/placeholder.jpg");
            $table->string('token_expiry');
            $table->integer('language_id')->default(0);
            $table->string('work')->default("");
            $table->string('school')->default("");
            $table->text('languages')->default("");
            $table->string('response_rate')->default("");
            $table->string('device_token')->default('');
            $table->enum('device_type',array('web','android','ios'));
            $table->enum('register_type',array('web','android','ios'));
            $table->enum('login_by',array('manual','facebook','google','twitter' , 'linkedin'));
            $table->string('social_unique_id')->default('');
            $table->enum('gender',array('male','female','others'));
            $table->double('latitude',15,8);
            $table->double('longitude',15,8);
            $table->text('full_address')->nullable();
            $table->string('street_details')->default("");
            $table->string('city')->default("");
            $table->string('state')->default("");
            $table->string('zipcode')->default("");
            $table->string('payment_mode');
            $table->string('provider_card_id')->default(0);
            $table->string('timezone')->default('America/Los_Angeles');
            $table->tinyInteger('registration_steps')->default(0);
            $table->integer('push_notification_status')->default(1);
            $table->integer('email_notification_status')->default(1);
            $table->integer('is_verified')->default(0);
            $table->string('verification_code')->default('');
            $table->string('verification_code_expiry')->default('');
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
        Schema::dropIfExists('providers');
    }
}
