<?php

namespace App\Services\v1\Admin;

use App\Models\v1\User;
use Illuminate\Support\Facades\Hash;

class ConfirmPasswordService
{
    public function addPasswordService($request)
    {
        $password_confirm = $request['password_confirm'];
        $password = $request['password'];
        $email = $request['email'];

        $user = User::chechEmail($email);

        if($user['password'] != null){
            return response()->json(['message' => 'Вы уже зарегистрированы'], 404);
        }

        if($password != $password_confirm){
            return response()->json(['message' => 'Пароли не совпадают!'], 404);
        }

        if($user){
            $password_hash = Hash::make($password);
            $result = User::where('id', $user['id'])->where('email', $email)->update([
                'password' => $password_hash,
                'status' => 1
            ]);
            if(!$result){
                return response()->json(['message' => 'Регистрация не выполнена!'], 404);
            }
            return response()->json(['message' => 'Вы зарегистрировались, поздравляю!']);
        }else{
            return response()->json(['message' => 'Пользователь не найден.'], 404);
    }

    }
}
