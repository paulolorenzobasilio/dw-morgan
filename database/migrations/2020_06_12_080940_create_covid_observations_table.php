<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_observations', function (Blueprint $table) {
            $table->id();
            $table->date('observation_date');
            $table->string('province_state')->nullable();
            $table->string('country');
            $table->integer('confirmed');
            $table->integer('deaths');
            $table->integer('recovered');
            $table->dateTime('last_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covid_observations');
    }
}
