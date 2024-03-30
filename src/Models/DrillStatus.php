<?php

namespace BCAUS\Seat\Structures\Models;

use Illuminate\Database\Eloquent\Model;

class DrillStatus extends Model
{
    protected $table = 'bcaus_structure_drillstatus';

    protected $primaryKey = 'd_structure_id';

    /**
     * @var array
     */
    protected $fillable = [
        'd_structure_id', 'notification_id', 'timestamp', 'ready_time'
    ];

    public function setReadyTimeAttribute($value)
    {
        $this->attributes['ready_time'] = is_null($value) ? null : carbon($value);
    }

    public function setTimestampAttribute($value)
    {
        $this->attributes['timestamp'] = is_null($value) ? null : carbon($value);
    }
}
