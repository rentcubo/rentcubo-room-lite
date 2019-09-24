<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id');
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('token');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('dob')->default("");
            $table->string('description')->default("");
            $table->string('mobile')->default("");
            $table->string('picture')->default(envfile('APP_URL')."/placeholder.jpg");
            $table->string('token_expiry');
            $table->tinyInteger('user_type')->default(0);
            $table->integer('language_id')->default(0);
            $table->string('device_token')->default('');
            $table->enum('device_type',array('web','android','ios'));
            $table->enum('register_type',array('web','android','ios'));
            $table->enum('login_by',array('manual','facebook','google','twitter' , 'linkedin'));
            $table->string('social_unique_id')->default('');
            $table->enum('gender',array('male','female','others'));
            $table->string('payment_mode');
            $table->string('user_card_id')->default(0);
            $table->string('timezone')->default('America/Los_Angeles');
            $table->tinyInteger('registration_steps')->default(0);
            $table->integer('push_notification_status')->default(1);
            $table->integer('email_notification_status')->default(1);
            $table->integer('is_verified')->default(0);
            $table->string('verification_code')->default('');
            $table->string('verification_code_expiry')->default('');
            $table->tinyInteger('status')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
