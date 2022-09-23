<?php

use Illuminate\Support\Facades\Route;


Route::group([
    'namespace' => 'BCAUS\Seat\Structures\Http\Controllers',
    'prefix' => 'tools/bcaus-structures',
    'middleware' => ['web', 'auth', 'locale', 'can:bcaus-structures.structures_view'],
], function () {

    Route::get('/')
        ->name('tools.bcaus-structures.list')
        ->uses('StructureController@index');
});
