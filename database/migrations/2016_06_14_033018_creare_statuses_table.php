<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreareStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("statuses", function(Blueprint $table){
            $table->increments("id");
            $table->integer("lended_staff_id")->unsigned();
            $table->integer("returned_staff_id")->unsigned()->nullable();
            $table->integer("lended_user_cd");
            $table->string("item_cd");
            $table->string("comment")->nullable();
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
        Schema::drop("statuses");
    }
}
