<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Comment extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'order_comments';

    public static function addComment($data)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $user_name = $user['name'];
        $user_position = $user['position'];


        if(!$position = RoleType::where('role', $user_position)->pluck('name')){
            return response()->json(['message' => 'Не верные данные.'], 404);
        }
        $comment = $data['comment'];
        $order_id = $data['order_id'];

        if(!Order::find($order_id)){
            return response()->json(['message' => 'Не верные данные.'], 404);
        }

        $result = self::insert([
            'user_id' => $user_id,
            'name' => $user_name,
            'comment' => $comment,
            'position' => $position[0],
            'order_id' => $order_id
        ]);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public static function list($data)
    {
        $order_id = $data['order_id'];

        if(!Order::find($order_id)){
            return response()->json(['message' => 'Не верные данные.'], 404);
        }
        return self::where('order_id', $order_id)->get()->toArray();
    }

    public static function deletedComment($id)
    {
        $user = Auth::user();

        $comment = self::find($id);

        if($comment){
            if($user['id'] != $comment['user_id']){
                return response()->json(['message' => 'Вы не можете удалить комментарий.'], 404);
            }
            $comment->delete();
            return true;
        }else{
            return response()->json(['message' => 'Не верные данные.'], 404);
        }
    }
}
