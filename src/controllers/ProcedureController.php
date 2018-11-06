<?php

namespace Bolboosch\Procedures\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Articles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcedureController extends Controller
{
	/**
	 * @internal This function will generate a list of procedures and
	 * let you make a migration for any procedure you wish to migrate
	 * @package Bolboosch\Procedures\Controllers
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		// Retrieve all procedures
		$listOfProcedures = $this->getAllMigrations();
		
		if($request->method() == 'POST') {
			
			// Store a list of failed migrations
			$failed = [];
			
			// Loop through the chosen procedures
			foreach($request->export as $export) {
				
				// Explode them so we have the database and
				$procedure = explode('|', $export);
				
				// Check if this migration already exists
				if($check = $this->checkIfMigrationExists($procedure[0])) {
					
					$failed[] = $check;
					
				} else {
					
					// Loop through the list and take the ones we are looking for
					foreach($listOfProcedures as $list) {
						
						// Found the item we want to export
						if($list->SPECIFIC_NAME == $procedure[0] && $list->ROUTINE_SCHEMA == $procedure[1]) {
							
							// Retrieve the parameters
							$parameters = $this->addParametersToList($list->SPECIFIC_NAME);
							
							// Prefix code with manditory code
							$prefix = 'DROP PROCEDURE IF EXISTS `'.$list->SPECIFIC_NAME.'`;
                        CREATE PROCEDURE `'.$list->SPECIFIC_NAME.'`('.$parameters.')
                        ';
							
							// Write a migration
							$file = file_get_contents(dirname(dirname(__FILE__)).'/templates/procedure.migration.php');
							$file = str_replace('{{PROCEDURE_FILE_NAME}}', studly_case($list->SPECIFIC_NAME), $file);
							$file = str_replace('{{PROCEDURE_NAME}}', $list->SPECIFIC_NAME, $file);
							$file = str_replace('{{PROCEDURE_CODE}}', $prefix.$list->ROUTINE_DEFINITION, $file);
							
							$new = fopen($this->basePath().date('Y_m_d_His', time()).'_'.$list->SPECIFIC_NAME.'_procedure.php', 'w+');
							fwrite($new, $file);
							
						}
						
					}
					
				}
				
			}
			
			return view('procedures::finished', compact('result'))->with('failed', $failed);
			
		}
		
		// Sort the results by database
		usort($listOfProcedures, function($a, $b) {
			return $a->ROUTINE_SCHEMA <=> $b->ROUTINE_SCHEMA;
		});
		
		// Loop through each list and add its parameters into the procedures array
		$this->addParameters($listOfProcedures);
		
		return view('procedures::index', compact('result'))->with('list', $listOfProcedures);
	}
	
	/**
	 * @internal This function will add the parameters for the individual procedure into the migration parameters
	 * @package Bolboosch\Procedures\Controllers
	 * @param $name
	 * @return string
	 */
	private function addParametersToList($name) : string
	{
		$parameters = '';
		
		$list = $this->query('select * from INFORMATION_SCHEMA.PARAMETERS where SPECIFIC_NAME="'.$name.'" order by ORDINAL_POSITION ');
		
		// Add the parameters
		foreach($list as $param) {
			
			$parameters .= $param->PARAMETER_MODE.' `'.$param->PARAMETER_NAME.'` '.$param->DATA_TYPE.',';
			
		}
		
		$parameters = rtrim($parameters, ',');
		
		return $parameters;
	}
	
	/**
	 * @internal This function will add the parameters to the array with procedures
	 * @package Bolboosch\Procedures\Controllers
	 * @param $listOfProcedures
	 * @return void
	 */
	private function addParameters(&$listOfProcedures) : void
	{
		foreach($listOfProcedures as $proc) {
			
			$proc->addedInformationData = $this->query('select * from INFORMATION_SCHEMA.PARAMETERS where SPECIFIC_NAME=\''.$proc->SPECIFIC_NAME.'\' order by ORDINAL_POSITION ');
			
		}
	}
	
	private function getAllMigrations()
	{
		return $this->query("SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE = N'PROCEDURE'");
	}
	
	/**
	 * @internal This function will check if a migration already exists
	 * @package Bolboosch\Procedures\Controllers
	 * @param $migration
	 * @return bool|string
	 */
	private function checkIfMigrationExists($migration)
	{
		$dir = opendir($this->basePath());
		
		while(($file = readdir($dir)) !== false) {
			
			if(strpos($file, $migration.'_procedure')) {
				
				return $file;
				
			}
			
		}
		
		return false;
	}
	
	/**
	 * @internal Wrapper for making a raw query
	 * @package Bolboosch\Procedures\Controllers
	 * @param $query  The MYSQL query to be executed
	 * @return mixed Laravel collection
	 */
	private function query($query)
	{
		return  DB::select(DB::raw($query));
	}
	
	/**
	 * @internal This function will return the correct base path
	 * @package Bolboosch\Procedures\Controllers
	 * @return string
	 */
	private function basePath()
	{
		return base_path().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;
	}
}