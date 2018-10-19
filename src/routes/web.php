<?php
define('bb_namespace', 'Bolboosch\Procedures\Controllers\\');


Route::get('bolboosch/migrate/procedures', bb_namespace.'ProcedureController@index');
Route::post('bolboosch/migrate/procedures', bb_namespace.'ProcedureController@index');