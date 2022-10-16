<?php

namespace BCAUS\Seat\Structures\Http\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class AthanorScope implements DataTableScope
{
    protected $athanorsOnly;

    public function __construct($athanorsOnly)
    {
        $this->athanorsOnly = $athanorsOnly;
    }

    public function apply($query)
    {
        if ($this->athanorsOnly === "true") {
            return $query
                ->where('type_id', '=', 35835);
        } else {
            return  $query;
        }
    }
}
