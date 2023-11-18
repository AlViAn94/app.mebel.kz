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

        File::put($filePath, file_get_contents($file));

        chmod($filePath, 0644);

        self::resizeImage($filePath);

        $fileLink = env('APP_URL') . ('/downloads/files/regmy/' . $fileName);

        if (File::exists($filePath)) {
            $existingRecord = self::where('user_id', $user['id'])
                ->where('created_at', 'LIKE', '%' . $date_add . '%')
                ->first();
            if($existingRecord){
                if($existingRecord['exit_time'] != null){
                    return response()->json(['message' => 'Вы уже зарегистрировали вход и выход за сегодня.'], 404);
                }else{
                    $result = self::where('user_id', $user['id'])
                        ->where('entrance_time', $existingRecord['entrance_time'])
                        ->update([
                            'exit_time' => $date,
                            'exit_file' => $fileLink
                        ]);
                    if (!$result) {
                        File::delete($filePath);
                        return response()->json(['message' => 'Не верный запрос.'], 404);
                    }

                }
                    return response()->json(['action' => 'exit']);
            }else{
                $result = self::insert([
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'entrance_time' => $date,
                    'entrance_file' => $fileLink
                ]);
                    if (!$result) {
                        File::delete($filePath);
                        return response()->json(['message' => 'Не верный запрос.'], 404);
                    }
                return response()->json(['action' => 'entrance']);
            }
        }
        return response()->json(['message' => 'Не верный запрос.'], 404);
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
    public static function resizeImage($filePath)
    {
        // Проверяем, существует ли файл по указанному пути
        if (file_exists($filePath)) {
            // Создаем объект Intervention Image из файла
            $image = Image::make($filePath);

            // Уменьшаем размер изображения до, например, 800x600
            $image->resize(200, 150, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Сохраняем измененное изображение в тот же файл
            $image->save();

            // Возвращаем путь к измененному файлу
            return $filePath;
        } else {
            // Обработка случая, если файл не существует
            return response()->json(['error' => 'Файл не существует.'], 404);
        }
    }

    public static function addTime($data = null)
    {
        if ($data != null) {
            $existingRecord = self::find($data['id']);
            $exist = $existingRecord[$data['action']];
            if (!$exist){
                // если есть запись
                $result = self::whereId($data['id'])
                    ->update([
                        $data['action']. '_' . 'time' => $data['time'],
                    ]);
                if(!$result){
                    return response()->json(['message' => 'bad request'], 400);
                }
                return response()->json(['message' => 'Время добавлено.']);
            } else {
                // если нет записи
                $png = 'https://files.app-mebel.kz/project_files/regmy.png';
                $result = self::whereId($data['id'])
                    ->update([
                        $data['action'] . '_' . 'time' => $data['time'],
                        $data['action']. '_' . 'file' => $png,
                    ]);
                if(!$result){
                    return response()->json(['message' => 'bad request'], 400);
                }
                return response()->json(['message' => 'Вы изменили время регистрации.']);
            }
        }

        return response()->json(['message' => 'bad request'], 400);
    }
}
