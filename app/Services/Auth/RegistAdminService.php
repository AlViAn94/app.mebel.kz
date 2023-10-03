<?php

namespace App\Services\Auth;

use App\Models\User;

class RegistAdminService
{
    public function registration($data): string
    {

        $iin = $data['iin'];
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $position = $data['position'];

        try {
            User::create([
                'iin' => $iin,
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'position' => $position
            ]);

            return response()->json(['message' => 'User registered successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed'], 500);
        }

    }
}
