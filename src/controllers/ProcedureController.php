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
    public function index(Request $request)
    {
        if($request->method() == 'POST') {

            // Store a list of failed migrations
            $failed = [];

            // Retrieve all procedures
            $listOfProcedures = $this->query("SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE = N'PROCEDURE'");

            // Loop through the chosen procedures
            foreach($request->export as $export) {

                // Explode them so we have the database and
                $procedure = explode('|', $export);

                // Check if this migration already exists
                if($check = $this->checkIfMigrationExists($procedure[0])) {

                    $failed[] = $check;

                } else {

                    // Loop through the list and take the ones we need
                    foreach($listOfProcedures as $list) {

                        // Found the item we want to export
                        if($list->SPECIFIC_NAME == $procedure[0] && $list->ROUTINE_SCHEMA == $procedure[1]) {

                            // Retrieve the parameters
                            $list->addedInformationData = $this->query('select * from INFORMATION_SCHEMA.PARAMETERS where SPECIFIC_NAME=\''.$list->SPECIFIC_NAME.'\' order by ORDINAL_POSITION ');

                            $parameters = '';

                            // Add the parameters
                            foreach($list->addedInformationData as $param) {

                                $parameters .= $param->PARAMETER_MODE.' `'.$param->SPECIFIC_NAME.'` '.$param->DATA_TYPE.',';

                            }

                            $parameters = rtrim($parameters, ',');

                            // Prefix code with manditory code
                            $prefix = 'DROP PROCEDURE IF EXISTS `'.$list->SPECIFIC_NAME.'`; 
                        CREATE PROCEDURE `'.$list->SPECIFIC_NAME.'`('.$parameters.')
                        ';

                            // Write a migration
                            $file = file_get_contents(dirname(dirname(__FILE__)).'/templates/procedure.migration.php');
                            $file = str_replace('{{PROCEDURE_FILE_NAME}}', studly_case($list->SPECIFIC_NAME), $file);
                            $file = str_replace('{{PROCEDURE_NAME}}', $list->SPECIFIC_NAME, $file);
                            $file = str_replace('{{PROCEDURE_CODE}}', $prefix.$list->ROUTINE_DEFINITION, $file);

                            $path = base_path().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;
                            $new = fopen($path.date('Y_m_d_His', time()).'_'.$list->SPECIFIC_NAME.'_procedure.php', 'w+');
                            fwrite($new, $file);

                        }

                    }

                }

            }

            return view('procedures::finished', compact('result'))->with('failed', $failed);

        }

        // Get a list of all procedures
        $listOfProcedures = $this->query("SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE = N'PROCEDURE'");

        // Sort the results by database
        usort($listOfProcedures, function($a, $b) {
            return $a->ROUTINE_SCHEMA <=> $b->ROUTINE_SCHEMA;
        });

        // Loop through each list and add its parameters
        foreach($listOfProcedures as $proc) {

            $proc->addedInformationData = $this->query('select * from INFORMATION_SCHEMA.PARAMETERS where SPECIFIC_NAME=\''.$proc->SPECIFIC_NAME.'\' order by ORDINAL_POSITION ');

        }

        return view('procedures::index', compact('result'))->with('list', $listOfProcedures);
    }

    private function query($query)
    {
        return  DB::select(DB::raw($query));
    }

    private function checkIfMigrationExists($migration)
    {
        // $migration.'_procedure.php'
        $path = base_path().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;
        $dir = opendir($path);

        while(($file = readdir($dir)) !== false) {

            if(strpos($file, $migration.'_procedure')) {

                return $file;

            }

        }

        return false;
    }
}