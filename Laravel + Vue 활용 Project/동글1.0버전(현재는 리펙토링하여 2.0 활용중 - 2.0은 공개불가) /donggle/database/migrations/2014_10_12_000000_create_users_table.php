<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->tinyInteger('register_kind')->defalut(1);
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('profile_img', 255)->nullable();
            $table->string('nickname', 255);
            $table->string('mobile_number', 255);
            $table->integer('level')->defalut(1);
            $table->char('post_num', 6);
            $table->string('addr1', 100);
            $table->string('addr2', 100);
            $table->string('extra_addr');
            $table->string('addr_jibeon');
            $table->tinyInteger('ad_agree')->defalut(1);
            $table->tinyInteger('status')->defalut(1);
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
