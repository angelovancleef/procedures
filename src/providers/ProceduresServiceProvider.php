<?php

namespace Bolboosch\Procedures\Providers;

use Illuminate\Support\ServiceProvider;

define('bb_path', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

class ProceduresServiceProvider extends ServiceProvider
{
	/**
	 * @internal This method is called after all other service providers have
	 * been registered, we will register our routes file here
	 * @package Bolboosch\Procedures\Providers
	 * @return void
	 */
    public function boot()
    {
        include bb_path.'routes'.DIRECTORY_SEPARATOR.'web.php';
    }
	
	/**
	 * @internal This method will register our controllers and views so they
	 * will be accessible in Laravel. Without this our views will not exist.
	 * @package Bolboosch\Procedures\Providers
	 * @return void
	 */
    public function register()
    {
        $this->app->make('Bolboosch\Procedures\Controllers\ProcedureController');
        $this->loadViewsFrom(bb_path.'views', 'procedures');
    }
}