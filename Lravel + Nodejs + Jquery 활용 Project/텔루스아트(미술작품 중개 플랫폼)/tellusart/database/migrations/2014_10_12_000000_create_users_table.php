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
    	Schema::defaultStringLength(255);
		
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
			$table->string('email',191)->unique();
			$table->timestamp('email_verified_at')->nullable();
            $table->string('name');
            $table->string('password');
			$table->string('profile_img');
			$table->string('nickname');
            $table->rememberToken();
			$table->text('mobile_number');
			$table->string('post_num');
			$table->string('addr1');
			$table->string('addr2');
			$table->string('extra_addr');
			$table->integer('level');
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
