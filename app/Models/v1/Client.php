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
            return response()->json([
                'message' => 'Данный клиент уже зарегистрирован!'
            ], 404);
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
            return response()->json(['message' => "Клиент не найден!"], 404);
        }
    }
    public static function updateClient($data)
    {
        $order = self::find($data['id']);

        if ($order) {
            $order->update([
                'iin' => $data['iin'],
                'email' => $data['email'],
                'name' => $data['name'],
                'surname' => $data['surname'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone']
            ]);

            return response()->json(["message" => "Клиент успешно обновлен!"]);
        } else {
            return response()->json(["message" => "Клиент не найден!"], 404);
        }
    }
}
