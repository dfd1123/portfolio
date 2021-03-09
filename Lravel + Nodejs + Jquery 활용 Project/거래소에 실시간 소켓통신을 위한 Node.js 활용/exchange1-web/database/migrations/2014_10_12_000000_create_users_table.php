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
			$table->string('username')->unique();
            $table->string('fullname');
			$table->string('password');
			$table->string('secret_pin')->nullable();
			$table->tinyInteger('level')->default(4);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country');
			$table->string('mobile_number')->nullable();
			$table->string('status')->default(1)->nullable()->index();
			$table->string('hash')->nullable();
			$table->string('wallet')->nullable();
			$table->string('ip')->nullable();
			$table->dateTime('time_signup')->nullable();
			$table->dateTime('time_signin')->nullable();
			$table->integer('time_activity')->nullable();
            $table->integer('referral_id')->nullable();
            $table->integer('market_type');
            $table->rememberToken();
            $table->tinyInteger('alarm_email')->default(0)->nullable();
            $table->tinyInteger('alarm_sms')->default(0)->nullable();
            $table->tinyInteger('alarm_io_email')->default(0)->nullable();
            $table->tinyInteger('alarm_io_sms')->default(0)->nullable();
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
