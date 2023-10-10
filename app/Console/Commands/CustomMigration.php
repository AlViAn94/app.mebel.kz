<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CustomMigration extends Command
{
    protected $signature = 'generate:migration-file {filename} {--table=} {--columns=*}';
    protected $description = 'Generate a migration file with custom content';

    public function handle()
    {
        $filename = $this->argument('filename');
        $tableName = $this->option('table');
        $columns = $this->option('columns');

        // Разделяем строку на массив с парами "тип:столбец"
        $columnPairs = explode(' ', $columns[0]);
        // Инициализируем массивы для типов и столбцов
        $name = [];
        $column = [];

        foreach ($columnPairs as $pair) {

            // Разделяем каждую пару на тип и столбец, используя ":"
            list($colName, $colType) = explode(':', $pair);

            if($colName != 'updated_at' && $colName != 'created_at' && $colName != 'curdate_at'){
                $name[] = $colName;
                $column[] = $colType;
            }
        }
        foreach ($column as $v) {
            if (strpos($v, '(') !== false) {
                list($colTypes, $colValue) = explode('(', $v);
                $trimmedString = rtrim($colValue, ')');
//                $notNull = rtrim($colValue, '-');
                switch ($colTypes){
                    case 'varchar':
                        $columnType[] = 'string';
                        $columnValue[] = $trimmedString;
                        break;
                    case 'bigint':
                        $columnType[] = 'bigInteger';
                        $columnValue[] = 0;
                        break;
                    case 'int':
                        $columnType[] = 'integer';
                        $columnValue[] = 0;
                        break;
                    case 'tinyint':
                        $columnType[] = 'tinyInteger';
                        $columnValue[] = 0;
                        break;
                    default:
                        $columnType[] = $colTypes;
                        $columnValue[] = $trimmedString;
                        break;
                }
            } else {
                // Если нет скобок, помещаем 0 в соответствующие массивы
                $columnType[] = $v;
                $columnValue[] = 0;
//                $columnNull[] = $notNull;
            }
        }
//        dd($columnNull);
        // Теперь $type содержит массив типов столбцов, а $column содержит массив имен столбцов
        // Ваша логика для генерации содержания миграции с использованием $filename, $tableName и $columns
        // Пример: генерация содержания миграции
        $migrationContent = $this->generateMigrationContent($tableName, $name, $columnType, $columnValue);

        $migrationFile = database_path('migrations/' . now()->format('Y_m_d_His') . '_' . $filename . '.php');

        File::put($migrationFile, $migrationContent);

        $this->info("Migration file {$filename}.php has been generated.");
    }


    protected function generateMigrationContent($tableName, $name, $columnType, $columnValue)
    {
        $migrationContent = '<?php' . PHP_EOL;
        $migrationContent .= PHP_EOL;
        $migrationContent .= 'use Illuminate\Database\Migrations\Migration;' . PHP_EOL;
        $migrationContent .= 'use Illuminate\Database\Schema\Blueprint;' . PHP_EOL;
        $migrationContent .= 'use Illuminate\Support\Facades\Schema;' . PHP_EOL;
        $migrationContent .= PHP_EOL;
        $migrationContent .= 'return new class extends Migration' . PHP_EOL;
        $migrationContent .= '{' . PHP_EOL;
        $migrationContent .= '    public function up()' . PHP_EOL;
        $migrationContent .= '    {' . PHP_EOL;
        $migrationContent .= '        Schema::create("' . $tableName . '", function (Blueprint $table) {' . PHP_EOL;

        $minLength = min(count($name), count($columnType), count($columnValue));

        for ($i = 0; $i < $minLength; $i++) {
            $colName = $name[$i];
            $colType = $columnType[$i];
            $colValue = $columnValue[$i];

            switch ($colName){
                case 'id':
                    $migrationContent .= '            $table->' . 'bigIncrements' . '("' . $colName . '")' . ';' . PHP_EOL;
                    break;
                case 'email':
                    $migrationContent .= '            $table->' . $colType . '("' . $colName . '")' . '->unique()'. ';' . PHP_EOL;
                    break;
                default:
                    if($colValue != 0){
                        $migrationContent .= '            $table->' . $colType . '("' . $colName . '", ' . $colValue . ')' . ';' . PHP_EOL;
                    }else{
                        $migrationContent .= '            $table->' . $colType . '("' . $colName . '")' . ';' . PHP_EOL;
                    }
            }
        }
        $migrationContent .= '            $table->' . 'timestamps' . '()' . ';' . PHP_EOL;

        $migrationContent .= '        });' . PHP_EOL;
        $migrationContent .= '    }' . PHP_EOL;
        $migrationContent .= PHP_EOL;
        $migrationContent .= '    public function down()' . PHP_EOL;
        $migrationContent .= '    {' . PHP_EOL;
        $migrationContent .= '        Schema::dropIfExists("' . $tableName . '");' . PHP_EOL;
        $migrationContent .= '    }' . PHP_EOL;
        $migrationContent .= '};';

        return $migrationContent;
    }

// php artisan generate:migration-file CreateCustomTable --table=custom_table --columns="string:name integer:age"
}
