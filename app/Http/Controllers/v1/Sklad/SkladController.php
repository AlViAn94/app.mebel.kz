<?php

namespace App\Http\Controllers\v1\Sklad;

use App\Http\Controllers\Controller;
use App\Imports\SkladImport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\v1\Sklad;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Sklad\SkladRequest;

class SkladController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index(Request $request)
    {
        return Sklad::list($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SkladRequest $request
     * @return JsonResponse
     */
    public function store(SkladRequest $request): JsonResponse
    {
        $sklad = new Sklad();

        $lastCode = Sklad::query()->max('code');
        $newCode = $lastCode + 1;

        $sklad->code = $newCode;
        $sklad->fill($request->all());
        $sklad->save();

        return response()->json(['success' => true, 'message' => 'Новый ресурс успешно сохранен'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SkladRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(SkladRequest $request, int $id): JsonResponse
    {
        $sklad = Sklad::query()->findOrFail($id);
        $sklad->fill($request->only('position', 'count', 'unit', 'price'));
        $sklad->save();

        return response()->json(['success' => true, 'message' => 'Обновление прошло успешно']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $sklad = Sklad::query()->findOrFail($id);

            $sklad->delete();

            return response()->json(['success' => true, 'message' => 'Запись успешно удалена']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ошибка при удалении записи'], 500);
        }
    }

    public function importXls(Request $request): \Maatwebsite\Excel\Excel
    {
       $result = Excel::import(new SkladImport, $request->file('file'), null, \Maatwebsite\Excel\Excel::XLS);

        return $result;
    }
}
