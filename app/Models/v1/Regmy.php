<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Illuminate\Support\Facades\File;

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
        $fileLink = env('APP_URL') . ('/downloads/files/regmy/' . $fileName);

        $existingRecord = self::where('user_id', $user['id'])
            ->where('created_at', 'LIKE', '%' . $date_add . '%')
            ->count();

        if (File::exists($filePath)) {
            switch ($existingRecord){
                case 0:
                        $result = self::insert([
                            'user_id' => $user['id'],
                            'name' => $user['name'],
                            'entrance_time' => $date_time,
                            'entrance_file' => $fileLink
                        ]);
                    if (!$result) {
                        File::delete($filePath);
                        return response()->json(['message' => 'Не верный запрос.'], 404);
                    }
                    return response()->json(['action' => 'entrance']);
                case 1:
                    $reg = self::where('user_id', $user['id'])->where('created_at', 'LIKE', '%' . $date_add . '%')->first();
                    if($reg['exit_time'] == null){
                        $result = self::where('user_id', $user['id'])
                            ->where('entrance_time', 'LIKE', '%' . $date_add . '%')
                            ->update([
                                'exit_time' => $date_time,
                                'exit_file' => $fileLink
                            ]);
                        if (!$result) {
                            File::delete($filePath);
                            return response()->json(['message' => 'Не верный запрос.'], 404);
                        }
                        return response()->json(['action' => 'exit']);
                    }
                    return response()->json(['message' => 'Вы уже зарегистрировали вход и выход за сегодня.'], 404);
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
        $result = self::where('user_id', $user['id'])->where('created_at', 'LIKE', '%' . $currentDate . '%')->get()->toArray();

        switch (count($result)){
            case 1:
                return response()->json(['date_entrance' => $result[0]['created_at']]);
            case 2:
                return response()->json(['date_exit' => $result[1]['created_at']]);
            default:
                $result = 'Зарегистрируйте вход';
        }
        return response()->json(['message' => $result]);
    }
}
