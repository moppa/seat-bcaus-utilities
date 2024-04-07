<?php

namespace BCAUS\Seat\Utilities\Models;

use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

class Structure extends CorporationStructure
{
    protected $table = 'corporation_structures';

    public function corporation()
    {
        return $this->hasOne(CorporationInfo::class, 'corporation_id', 'corporation_id');
    }

    public function drill_status()
    {
        return $this->hasOne(DrillStatus::class, 'd_structure_id', 'structure_id');
    }
}
