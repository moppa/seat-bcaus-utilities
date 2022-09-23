<?php

namespace BCAUS\Seat\Structures\Models;

use Illuminate\Database\Eloquent\Model;

class DrillStatus extends Model
{
    protected $table = 'v_structure_drillstatus';

    public function setReadyTimeAttribute($value)
    {
        $this->attributes['ready_time'] = is_null($value) ? null : carbon($value);
    }

    public function setTimestampAttribute($value)
    {
        $this->attributes['timestamp'] = is_null($value) ? null : carbon($value);
    }
}
