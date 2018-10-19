<?php

namespace Bolboosch\Procedures\Providers;

use Illuminate\Support\ServiceProvider;

define('bb_path', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

class ProceduresServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include bb_path.'routes'.DIRECTORY_SEPARATOR.'web.php';
    }

    public function register()
    {
        $this->app->make('Bolboosch\Procedures\Controllers\ProcedureController');
        $this->loadViewsFrom(bb_path.'views', 'procedures');
    }
}