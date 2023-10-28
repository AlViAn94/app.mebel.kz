<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\User;
use App\Services\v1\Admin\StoreUsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\LaravelIdea\Helper\App\Models\v1\_IH_User_C|User[]
     */
    public function index(Request $request)
    {
        return User::list($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, StoreUsersService $service)
    {
        return $service->addUser($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $admin = Auth::user();

        if($admin['position'] != 'admin'){
            return response()->json(['message' => 'У вас нет прав на это действие.'], 404);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден.'], 404);
        }

        $user->password = null;
        $user->connection_id = null;
        $user->status = 2;
        $user->save();

        return response()->json(['message' => 'Сотрудник уволен.']);
    }
}
