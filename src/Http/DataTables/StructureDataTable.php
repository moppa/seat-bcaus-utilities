<?php

namespace BCAUS\Seat\Structures\Http\DataTables;

use BCAUS\Seat\Structures\Models\Structure;
use Yajra\DataTables\Services\DataTable;

class StructureDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->corporation])->render();
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName])->render();
            })
            ->editColumn('state', function ($row) {
                return ucfirst(str_replace('_', ' ', $row->state));
            })
            ->editColumn('fuel_expires', function ($row) {
                if ($row->fuel_expires)
                    return view('web::partials.date', ['datetime' => $row->fuel_expires])->render();

                return trans('web::seat.low_power');
            })
            ->editColumn('reinforce_hour', function ($row) {
                return view('web::corporation.structures.partials.reinforcement', compact('row'))->render();
            })
            ->editColumn('services', function ($row) {
                return view('web::corporation.structures.partials.services', compact('row'))->render();
            })
            ->editColumn('drill_status.ready_time', function ($row) {
                if ($row->drill_status) {
                    if ($row->drill_status->ready_time) {
                        return view('web::partials.date', ['datetime' => $row->drill_status->ready_time])->render();
                    }
                    return '<b>*NOT ACTIVE*</b>';
                }
                return '<span>N/A</span>';
            })
            ->filterColumn('services', function ($query, $keyword) {
                $query->whereHas('services', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['type.typeName', 'fuel_expires', 'offline_estimate', 'reinforce_hour', 'services', 'corporation.name', 'drill_status.ready_time'])
            ->make(true);
    }

    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addTableClass('table-striped table-hover')
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    public function query()
    {
        return Structure::with('info', 'type', 'solar_system', 'services', 'corporation', 'drill_status');
    }

    public function getColumns()
    {
        return [
            ['data' => 'solar_system.name', 'title' => trans('web::seat.location')],
            ['data' => 'info.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'type.typeName', 'title' => trans_choice('web::seat.type', 1)],
            ['data' => 'corporation.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'state', 'title' => trans('web::seat.state')],
            ['data' => 'fuel_expires', 'title' => trans('web::seat.offline')],
            ['data' => 'drill_status.ready_time', 'title' => 'Extraction'],
            ['data' => 'reinforce_hour', 'title' => trans('web::seat.reinforce_week_hour')],
            ['data' => 'services', 'title' => trans_choice('web::seat.services', 0), 'orderable' => false],
        ];
    }
}
