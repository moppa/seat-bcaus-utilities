<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDrillStatusTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bcaus_structure_drillstatus')) {
            Schema::create('bcaus_structure_drillstatus', function (Blueprint $table) {

                $table->bigInteger('d_structure_id')->primary();
                $table->bigInteger('notification_id');
                $table->dateTime('timestamp');
                $table->dateTime('ready_time')->nullable();

                $table->timestamps();
            });

            DB::statement("
                INSERT INTO bcaus_structure_drillstatus (
                    d_structure_id,
                    notification_id,
                    timestamp,
                    ready_time
                )
                SELECT
                    v_structure_id,
                    1,
                    timestamp,
                    ready_time
                FROM v_structure_drillstatus
            ");
        }
    }

    public function down()
    {
    }
}
