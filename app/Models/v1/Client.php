<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iin',
        'name',
        'surname',
        'lastname',
        'phone',
        'email'
    ];

    public static function newClient($data)
    {
        $existingRecord = static::where('iin', $data['iin'])->first();

        if (!$existingRecord) {
            $newRecord = new static;
            $newRecord->fill($data);
            $newRecord->save();
            return $newRecord;
        }else{
            return [
                'message' => 'Данный клиент уже зарегистрирован!',
                'iin' => $data['iin']
            ];
        }
    }

    public static function checkClient($data)
    {
        $existingRecord = static::where('iin', $data['iin'])
            ->orWhere('phone', $data['phone'])
            ->first();
        if($existingRecord)
        {
            return $existingRecord;
        }else{
            return false;
        }
    }
}
