<?php

use Illuminate\Support\Facades\Route;


Route::group([
    'namespace' => 'BCAUS\Seat\Utilities\Http\Controllers\Structure',
    'prefix' => 'bcaus-structures',
    'middleware' => ['web', 'auth', 'locale', 'can:bcaus-structures.structures_view'],
], function () {

    Route::get('/')
        ->name('bcaus-structures.list')
        ->uses('StructureController@index');
});
