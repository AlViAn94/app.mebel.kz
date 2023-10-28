<?php

namespace App\Services\v1\Admin;

use App\Models\v1\Role;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;

class StoreUsersService
{
    public function addUser($request)
    {
        $iin = $request['iin'];
        $name = $request['name'];
        $phone = $request['phone'];
        $address = $request['address'];
        $position = $request['position'];
        $position_name = $request['position_name'];
        $salary = $request['salary'];
        $email = $request['email'];
        $user = Auth::user();
        $connection = $user['connection_id'];

        $connect = User::checkUser($iin);

        if($connect){
            if($connect['connection_id'] == $connection){
                return response()->json(['message' => 'Уже есть в нашей базе.'], 404);
            }
        }
        $result = User::insertGetId([
            'iin' => $iin,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'position' => $position,
            'email' => $email,
            'connection_id' => $connection,
            'salary' => $salary,
            'status' => 3
        ]);

        Role::insert([
            'user_id' => $result,
            'role' => $position,
            'name' => $position_name
        ]);
        if($result){
            return response()->json(['message' => 'Успешно добавлен.']);
        }
        return response()->json(['message' => 'Не удалось добавить.'], 404);
    }
}
