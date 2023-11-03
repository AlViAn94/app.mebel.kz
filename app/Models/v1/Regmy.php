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

    public static function regMyImportPhoto($file, $action)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $directoryPath = public_path('downloads/files/regmy');

        $date = Carbon::now();
        $date_time = $date->format('Y-m-d_H:m:s');
        $date_add = $date->format('Y-m-d');

        $existingRecord = self::where('user_id', $user_id)
            ->where('action', $action)
            ->where('created_at', 'LIKE', '%' . $date_add . '%')
            ->get();

        if ($existingRecord->count() > 0) {
            return response()->json(['message' => 'Вы уже зарегистрировались.'], 404);
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
        return response()->json(['message' => 'Добро пожаловать.']);
    }

}
