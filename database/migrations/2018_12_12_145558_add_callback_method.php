<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallbackMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ctrts_schedules', function (Blueprint $table) {
            $table->string('entity_callback_method')->after('entity_id')->default('POST');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ctrts_schedules', function (Blueprint $table) {
            $table->dropColumn('entity_callback_method');
        });
    }
}
