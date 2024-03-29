<?php

namespace App\Imports;

use App\Models\v1\Sklad;
use Maatwebsite\Excel\Concerns\ToModel;

class SkladImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $lastCode = Sklad::max('code');
        $newCode = $lastCode + 1;

        if (is_numeric($row[1])) {
            return new Sklad([
                'position' => $row[0],
                'count' => $row[1],
                'unit' => $row[3],
                'price' => $row[5],
                'code' => $newCode
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
