<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

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
//        $user = Auth::user();
//
//        $directoryPath = public_path('downloads/files/regmy');
//
//        $date = Carbon::now();
//        $date_time = $date->format('Y-m-d_H:m:s');
//        $date_add = $date->format('Y-m-d');
//
//        if (!File::isDirectory($directoryPath)) {
//            File::makeDirectory($directoryPath, 0755, true, true);
//        }
//
//        $extension = $file->getClientOriginalExtension();
//
//        $fileName = $user['iin'] . '_' . $date_time . '.' . $extension;
//        $filePath = $directoryPath . '/' . $fileName;
//
//        $image = Image::make($file);
//        $image->resize(800, 600, function ($constraint) {
//            $constraint->aspectRatio();
//            $constraint->upsize();
//        });
//        $image->save($filePath);
//
//        File::put($filePath, file_get_contents($file));
//        $fileLink = env('APP_URL') . ('/downloads/files/regmy/' . $fileName);
//
//
//
//        if (File::exists($filePath)) {
//            $existingRecord = self::where('user_id', $user['id'])
//                ->where('created_at', 'LIKE', '%' . $date_add . '%')
//                ->first();
//            if($existingRecord){
//                if($existingRecord['exit_time'] != null){
//                    return response()->json(['message' => 'Вы уже зарегистрировали вход и выход за сегодня.'], 404);
//                }else{
//                    $result = self::where('user_id', $user['id'])
//                        ->where('entrance_time', $existingRecord['entrance_time'])
//                        ->update([
//                            'exit_time' => $date,
//                            'exit_file' => $fileLink
//                        ]);
//                    if (!$result) {
//                        File::delete($filePath);
//                        return response()->json(['message' => 'Не верный запрос.'], 404);
//                    }
//                }
//                    return response()->json(['action' => 'exit']);
//            }else{
//                $result = self::insert([
//                    'user_id' => $user['id'],
//                    'name' => $user['name'],
//                    'entrance_time' => $date,
//                    'entrance_file' => $fileLink
//                ]);
//                    if (!$result) {
//                        File::delete($filePath);
//                        return response()->json(['message' => 'Не верный запрос.'], 404);
//                    }
//                return response()->json(['action' => 'entrance']);
//            }
//        }
//        return response()->json(['message' => 'Не верный запрос.'], 404);
        $user = Auth::user();

        $directoryPath = public_path('downloads/files/regmy');

        $date = Carbon::now();
        $date_time = $date->format('Y-m-d_H:m:s');
        $date_add = $date->format('Y-m-d');

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $extension = $file->getClientOriginalExtension();

        $fileName = $user['iin'] . '_' . $date_time . '.' . $extension;
        $filePath = $directoryPath . '/' . $fileName;

        // Изменения для использования Intervention Image
        $image = Image::make($file);
        $image->resize(300, 200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($filePath);

        $fileLink = env('APP_URL') . ('/downloads/files/regmy/' . $fileName);

        $existingRecord = self::where('user_id', $user['id'])
            ->where('created_at', 'LIKE', '%' . $date_add . '%')
            ->first();

        if ($existingRecord) {
            if ($existingRecord['exit_time'] != null) {
                return response()->json(['message' => 'Вы уже зарегистрировали вход и выход за сегодня.'], 404);
            } else {
                $result = self::where('user_id', $user['id'])
                    ->where('entrance_time', $existingRecord['entrance_time'])
                    ->update([
                        'exit_time' => $date,
                        'exit_file' => $fileLink
                    ]);

                if (!$result) {
                    // Удаляем изображение, если не удалось обновить запись
                    File::delete($filePath);
                    return response()->json(['message' => 'Не верный запрос.'], 404);
                }
            }

            return response()->json(['action' => 'exit']);
        } else {
            $result = self::insert([
                'user_id' => $user['id'],
                'name' => $user['name'],
                'entrance_time' => $date,
                'entrance_file' => $fileLink
            ]);

            if (!$result) {
                // Удаляем изображение, если не удалось вставить новую запись
                File::delete($filePath);
                return response()->json(['message' => 'Не верный запрос.'], 404);
            }

            return response()->json(['action' => 'entrance']);
        }
    }

    public static function getList($data)
    {
        $period = $data['period'];
        $search = $data['search'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $count = $data['count'];
        $page = $data['page'];

        if (empty($sort)) {
            $sort = 'created_at';
        }

        $firstItemNumber = ($page - 1) * $page + 1;

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

        $query = self::where('created_at', 'LIKE', '%' . $currentDate . '%')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });

        $existingRecord = $query
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($count, ['*'], 'page', $page);

        foreach ($existingRecord as $item) {
            $item->order_number = $firstItemNumber++;
        }
        return $existingRecord;
    }

    public static function checkAction()
    {
        $date = Carbon::now();
        $currentDate = $date->format('Y-m-d');
        $user = Auth::user();
        $result = self::where('user_id', $user['id'])->where('created_at', 'LIKE', '%' . $currentDate . '%')->first();

        if($result){
            if($result['exit_time'] != null){
                return response()->json(['date_exit' => $result['exit_time']]);
            }
            if($result['entrance_time'] != null){
                return response()->json(['date_entrance' => $result['entrance_time']]);
            }
            return response()->json(['message' => $result]);
        }
        return response()->json(['message' => 'Зарегистрируйте вход.']);
    }
}
