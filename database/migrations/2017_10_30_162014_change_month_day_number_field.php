<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMonthDayNumberField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ctrts_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('ctrts_schedules', 'day_of_month')) {
                $table->renameColumn('day_of_month', 'day_number');
            } else {
                $table->unsignedTinyInteger('day_number')->nullable();
            }

            if (Schema::hasColumn('ctrts_schedules', 'week_of_month')) {
                $table->renameColumn('week_of_month', 'week_number');
            } else {
                $table->unsignedTinyInteger('week_number')->nullable();
            }
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
