<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallbackUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ctrts_schedules', function (Blueprint $table) {
            $table->int('schedulable_id')->nullable()->change();
            $table->string('scheduleable_type', 100)->nullable()->change();
            $table->string('entity_id', 100)->nullable();
            $table->string('entity_callback_url', 100)->nullable();
            $table->text('entity_callback_params')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
