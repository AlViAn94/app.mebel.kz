<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserActionLog extends Model
{
    use HasFactory;

    protected $table = 'user_action_logs';

    protected $fillable = [
        'name',
        'user_id',
        'action_description',
    ];

    public static function createdLog($text): void
    {
        $name = Auth::user()->name;
        $id = Auth::user()->id;

        $data = [
            'name' => $name,
            'user_id' => $id,
            'action_description' => $text,
        ];
        self::insert($data);
    }
}
