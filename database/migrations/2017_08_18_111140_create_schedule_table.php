<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('schedulable');
            $table->string('timezone', 40);
            $table->string('type', 20)->default('periodic');
            $table->dateTime('range_start');
            $table->dateTime('range_end');
            $table->time('time_of_day')->default('00:00:00');
            $table->unsignedSmallInteger('interval')->default(1);
            $table->string('period');
            $table->unsignedTinyInteger('day_of_month')->nullable();
            $table->unsignedTinyInteger('week_of_month')->nullable();
            foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day) {
                $table->unsignedTinyInteger($day)->default(1);
            }
            foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'nov', 'dec'] as $month) {
                $table->unsignedTinyInteger($month)->default(1);
            }
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
