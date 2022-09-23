<?php

namespace BCAUS\Seat\Structures\Http\Controllers;

use Illuminate\Http\Request;
use BCAUS\Seat\Structures\Http\DataTables\StructureDataTable;
use Seat\Web\Http\Controllers\Controller;

class StructureController extends Controller
{
    public function index(Request $request, StructureDataTable $dataTable)
    {
        return $dataTable
            ->render('bcaus-structures::structures.list');
    }
}
