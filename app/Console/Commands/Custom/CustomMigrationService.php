<?php

namespace App\Console\Commands\Custom;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class CustomMigrationService
{

/*
 * Кастомная миграция по созданию уже готовой таблицы
 * Заполняет сразу тип и количество места
 */

    public function actionCustomMigration(Request $request)
    {

        $table = $request->table_name;

        return $this->makeMigrate($table);

    }

    public function actionMassMigration(Request $request)
    {
        $tables = Schema::getAllTables();

        $db_name = $request['db_name'];

        $db = 'Tables_in_' . $db_name;

        $excludedTables = [
            'oauth_personal_access_clients',
            'personal_access_tokens',
            'oauth_refresh_tokens',
            'oauth_access_tokens',
            'oauth_auth_codes',
            'password_resets',
            'oauth_clients',
            'migrations',
            'users',
            ];

        foreach ($tables as $v) {
            $tabName = $v->$db;
            if (!$this->isExcluded($tabName, $excludedTables)) {
                $this->makeMigrate($tabName);
            }
        }
    }

    private function isExcluded($tableName, $excludedTables)
    {
        return in_array($tableName, $excludedTables);
    }

    static function makeMigrate($table)
    {
        $columns = Schema::getColumnListing($table);
        // Получаем информацию о внешних ключах таблицы (если они есть)
        $tableF = Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails($table);

        $foreignKeys = $tableF->getForeignKeys();
        print_r($foreignKeys['_localColumnNames:protected']); exit();

        $tableName = 'generate:migration-file ' . $table .' --table=' . $table . ' --columns=';
        foreach($columns as $v){
            $columnInfo = DB::select("SHOW COLUMNS FROM " .  $table . " WHERE Field = '$v'");
            $colType[] = $columnInfo[0];
        }

        foreach ($colType as $v){
            $spacePosition = strpos($v->Type, ' '); // Находим позицию пробела
            if ($spacePosition !== false) {
                $result = strstr($v->Type, ' ', true);
                $value = $v->Field . ':' . $result;
            } else {
                $value = $v->Field . ':' . $v->Type;

            }

            $columnStatus[] = $value;
        }

        $combinedString = implode(' ', $columnStatus);

        $fullRequest = $tableName . '"' . $combinedString . '"';

        try{
            Artisan::call($fullRequest);
            $output = Artisan::output();
            return $output;
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
