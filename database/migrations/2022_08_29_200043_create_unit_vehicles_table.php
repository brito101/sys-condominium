<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('title');
            $table->string('color');
            $table->string('plate');
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
        Schema::dropIfExists('unit_vehicles');
    }
}
