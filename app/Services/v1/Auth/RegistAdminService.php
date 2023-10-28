<?php

namespace App\Services\v1\Auth;

use App\Models\v1\Role;
use App\Models\v1\User;
use Illuminate\Support\Facades\DB;

class RegistAdminService
{
    public function registration($data): string
    {
        try {
        DB::transaction(function () use ($data) {

            $iin = $data['iin'];
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['password'];
            $position = $data['position'];
            $phone = $data['phone'];

            $user = User::create([
                'iin' => $iin,
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'position' => $position,
                'phone' => $phone
            ]);
            $result = DB::table('role_type')->select('name')->where('role', $position)->first();

            $model['id'] = $user['id'];
            $model['role'] = $user['position'];
            $model['name'] = $result->name;

            Role::createdRole($model);

        });
            return response()->json(['message' => 'Успешно зарегистрирован!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e], 500);
        }

    }
}
