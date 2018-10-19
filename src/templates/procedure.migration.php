<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {{PROCEDURE_FILE_NAME}}Procedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        
    {{PROCEDURE_CODE}}
    
SQL;
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
DROP PROCEDURE IF EXISTS `{{PROCEDURE_NAME}}`;
SQL;
        DB::unprepared($sql);
    }
}