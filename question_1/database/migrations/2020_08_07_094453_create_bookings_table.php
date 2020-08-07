<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cleaner_id');
            $table->foreign('cleaner_id')
                ->references('id')
                ->on('cleaners');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
            $table->time('start');
            $table->time('end');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
