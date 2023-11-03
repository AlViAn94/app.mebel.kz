<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class Regmy extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'reg_my';

    protected $fillable = [
        'user_id',
        'action',
        'file'
    ];

    public static function regMyImportPhoto($file)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $directoryPath = public_path('downloads/files/regmy');

        $date = Carbon::now();
        $date_time = $date->format('Y-m-d_H:m:s');
        $date_add = $date->format('Y-m-d');

        $existingRecord = self::where('user_id', $user_id)
            ->where('created_at', 'LIKE', '%' . $date_add . '%')
            ->count();
        switch ($existingRecord){
            case 0:
                $action = 'entrance';
            break;
            case 1:
                $action = 'exit';
            break;
            default:
                return response()->json(['message' => 'Вы уже зарегистрировали вход и выход за сегодня.'], 404);
        }

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $extension = $file->getClientOriginalExtension();

        $fileName = $user['iin'] . '_' . $date_time . '.' . $extension;
        $filePath = $directoryPath . '/' . $fileName;

        File::put($filePath, file_get_contents($file));
        $fileLink = env('APP_URL') . ('/downloads/files/regmy/' . $fileName);

        if (File::exists($filePath)) {
            $result = self::insert([
                'user_id' => $user_id,
                'action' => $action,
                'file' => $fileLink
            ]);

            if (!$result) {
                File::delete($filePath);
                return response()->json(['message' => 'Не верный запрос.'], 404);
            }
        }
        if($action == 'entrance'){
            return response()->json(['message' => 'Добро пожаловать.']);

        }else{
            return response()->json(['message' => 'До свиданья.']);
        }
    }

    public static function getList($data)
    {
        $period = $data['period'];

        $date = Carbon::now();


        switch ($period){
            case 'day':
                $currentDate = $date->format('Y-m-d');
                break;

            case 'month':
                $currentDate = $date->format('Y-m');
                break;

            default:
                $currentDate = $period;
                break;
        }

        $existingRecord = self::where('created_at', 'LIKE', '%' . $currentDate . '%')
            ->get();

        foreach ($existingRecord as $item) {
            $user = User::where('id', $item['user_id'])->first();
            $item->name = $user['name'];
            $item->position = $user['position'];
            $item->iin = $user['iin'];
        }
        return $existingRecord;
    }
}
