<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('Monday')->default(0);
            $table->boolean('Tuesday')->default(0);
            $table->boolean('Wednesday')->default(0);
            $table->boolean('Thursday')->default(0);
            $table->boolean('Friday')->default(0);
            $table->boolean('Saturday')->default(0);
            $table->boolean('Sunday')->default(0);
            $table->Integer('Restau_Id')->unsigned();
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
        Schema::dropIfExists('days');
    }
}
