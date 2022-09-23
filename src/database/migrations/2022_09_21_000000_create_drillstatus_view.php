<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDrillStatusView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_structure_drillstatus as
            with latest_event as (
            SELECT q.*,ROW_NUMBER() OVER (PARTITION BY v_structure_id ORDER BY `timestamp` DESC) AS rn 
            FROM 
                (
                select SUBSTRING_INDEX(SUBSTRING_INDEX(cn.`text` , 'structureID: ', -1), '\n', 1) as v_structure_id, FROM_UNIXTIME((SUBSTRING_INDEX(SUBSTRING_INDEX(cn.`text` , 'readyTime: ', -1), '\n', 1) / 10000000) - 11644473600)  as ready_time, 1 as event, `timestamp` from character_notifications cn where `type` = 'MoonminingExtractionStarted'
                UNION 
                select SUBSTRING_INDEX(SUBSTRING_INDEX(cn.`text` , 'structureID: ', -1), '\n', 1) as v_structure_id, null as ready_time, 0 as event, `timestamp` from character_notifications cn where `type` = 'MoonminingExtractionFinished'
                UNION 
                select SUBSTRING_INDEX(SUBSTRING_INDEX(cn.`text` , 'structureID: ', -1), '\n', 1) as v_structure_id, null as ready_time, -1 as event, `timestamp` from character_notifications cn where `type` = 'MoonminingExtractionCancelled'
                )
                as q
                GROUP BY v_structure_id, event, `timestamp`, ready_time
            )
            
            select v_structure_id, event, `timestamp`, ready_time  from latest_event WHERE rn = 1
        ");
    }

    public function down()
    {
    }
}
