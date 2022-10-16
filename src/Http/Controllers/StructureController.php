<?php

namespace BCAUS\Seat\Structures\Http\Controllers;

use Illuminate\Http\Request;
use BCAUS\Seat\Structures\Http\DataTables\StructureDataTable;
use BCAUS\Seat\Structures\Http\DataTables\Scopes\AthanorScope;
use Seat\Web\Http\Controllers\Controller;

class StructureController extends Controller
{
    public function index(Request $request, StructureDataTable $dataTable)
    {
        $status = $request->input('athanorsOnly');
        return $dataTable
            ->addScope(new AthanorScope($status))
            ->render('bcaus-structures::structures.list');
    }
}
