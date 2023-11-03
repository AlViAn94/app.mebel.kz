<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use function PHPUnit\Framework\fileExists;

class Regmy extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'reg_my';

    protected $fillable = [
        'user_id',
        'action',
        'file'
    ];

    public static function regMyImportPhoto($file, $fileName, $action)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $filePath = public_path('downloads/files/regmy/' . $fileName);
        file_put_contents($filePath, $file);
        $fileLink = env('APP_URL') . ('/downloads/files/regmy/' . $fileName);
        if(fileExists($filePath))
        {
            $result = self::insert([
                'user_id' => $user_id,
                'action' => $action,
                'file' => $fileLink
            ]);
            if(!$result){
                unlink($filePath);
                return response()->json(['message' => 'Не верный запрос.'], 404);
            }
        }
        return response()->json(['message' => 'Добро пожаловать.']);
    }

}
