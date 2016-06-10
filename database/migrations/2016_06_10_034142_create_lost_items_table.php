<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lost_item_name');
            $table->integer('reciept_staff_id')->unsigned();
            $table->integer('delivery_staff_id')->unsigned()->nullable();
            $table->integer('place_id')->unsigned();
            $table->integer('student_id')->unsigned()->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
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
        Schema::drop('lost_items');
    }
}
