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
        Schema::create('ctrts_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('scheduleable');
            $table->string('timezone', 40);
            $table->string('type', 20)->default('periodic');
            $table->dateTime('range_start');
            $table->dateTime('range_end');
            $table->time('time_of_day')->default('00:00:00');
            $table->unsignedSmallInteger('interval')->default(1);
            $table->string('period');
            $table->string('occurrence')->nullable();
            $table->unsignedTinyInteger('day_number')->nullable();
            $table->string('week_of_month')->nullable();
            foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day) {
                $table->unsignedTinyInteger($day)->default(1);
            }
            foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $month) {
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
        Schema::dropIfExists('ctrts_schedules');
    }
}
