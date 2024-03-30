<?php

namespace App\Imports;

use App\Models\v1\Sklad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Concerns\ToModel;

class SkladImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Sklad|JsonResponse
     */
    public function model(array $row): Sklad|JsonResponse
    {
        $lastCode = Sklad::query()->max('code');
        $newCode = $lastCode + 1;

        if (is_numeric($row[1])) {
            return new Sklad([
                'position' => $row[0],
                'count' => $row[1],
                'unit' => $row[3],
                'price' => $row[5],
                'code' => $newCode
            ]);
        }else{
            return response()->json(['message' => 'error']);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
