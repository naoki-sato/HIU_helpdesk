<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');            // ex. 佐藤 直己
            $table->string('email')->unique(); // ex. s1312007@s.do-johodai.ac.jp
            $table->string("staff_cd")->unique();        // ex. s1312007
            $table->integer("phone_no")->nullable();
            $table->string('password');
            $table->string('role'); // admin, manager , staff
            $table->softDeletes();
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
        Schema::drop('admins');
    }
}
