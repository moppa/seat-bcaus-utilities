@extends('web::layouts.grids.12')

@section('title', 'Structures')
@section('page_header', 'Structures')

@section('full')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Structures</h3>
        </div>
        <div class="card-body">

            {{ $dataTable->table() }}

        </div>
    </div>

@stop

@push('javascript')
    {!! $dataTable->scripts() !!}
@endpush
