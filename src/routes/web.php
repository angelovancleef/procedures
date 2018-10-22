<?php
// Define a namespace so we dont have to repeat ourselfs for future routes
define('bb_namespace', 'Bolboosch\Procedures\Controllers\\');


Route::get('bolboosch/migrate/procedures', bb_namespace.'ProcedureController@index');
Route::post('bolboosch/migrate/procedures', bb_namespace.'ProcedureController@index');