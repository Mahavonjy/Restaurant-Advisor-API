<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('localisation');
            $table->string('web_site')->nullable();
            $table->Integer('User_id')->unsigned();
            $table->Integer('téléphone')->nullable();
            $table->time('Week_end_time')->nullable();
            $table->string('description')->nullable();
            $table->time('Week_start_time')->nullable();
            $table->time('Semaine_end_time')->nullable();
            $table->time('Semaine_start_time')->nullable();
            $table->Integer('note')->unsigned()->default(0);
            $table->Integer('Avis')->unsigned()->default(0);
            $table->Integer('Visited')->unsigned()->default(0);
            $table->Integer('Menu_Number')->unsigned()->default(0);
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
        Schema::dropIfExists('restaurants');
    }
}
