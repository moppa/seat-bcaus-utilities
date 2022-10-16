@extends('web::layouts.grids.12')

@section('title', 'Structures')
@section('page_header', 'Structures')

@section('full')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Structures</h3>
            <div class="card-tools">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="structure-athanor-filter">
                    <label class="form-check-label">Only Athanors</label>
                </div>
            </div>
        </div>
        <div class="card-body">

            {{ $dataTable->table() }}

        </div>
    </div>

@stop

@push('javascript')
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name="structure-athanor-filter"]').on('change', function() {
                window.LaravelDataTables["dataTableBuilder"].ajax.reload();
            });
        });
    </script>
@endpush
