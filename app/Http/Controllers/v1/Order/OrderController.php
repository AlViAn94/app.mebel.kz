<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Other\iDRequest;
use App\Models\v1\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function actionOrderType()
    {
        return DB::table('orders_type')->select('id', 'name')->get();
    }

    public function actionProcessing(IdRequest $request)
    {
        $id = $request['id'];
        $status = 1;
        return Order::where('id', $id)->update(['status' => $status]);
    }

    public function actionConfirmOrder(IdRequest $request)
    {
        $id = $request['id'];
        $status = 2;
        return Order::where('id', $id)->update(['status' => $status]);
    }

    public function actionOrderSort(Request $request)
    {
        return Order::getSortOrder($request->all());
    }

    public function actionUpdate(Request $request)
    {
        return Order::updateOrder($request->all());
    }

    public function actionTest()
    {
        // Выполните SQL-запрос и получите результаты
        $results = DB::select('SELECT iin, name, email FROM users');
        $date = date('Y:m:d_H:m:s');
        // Создайте уникальное имя файла на основе временной метки
        $fileName = "export_{$date}.csv";

        // Определите путь к файлу в директории public
        $filePath = public_path("downloads/{$fileName}");

        // Откройте файл для записи
        $fp = fopen($filePath, 'w');

        // Запишите названия столбцов в CSV
        $column_names = array('iin', 'name', 'email');
        fputcsv($fp, $column_names);

        // Запишите данные в CSV
        foreach ($results as $row) {
            fputcsv($fp, (array) $row);
        }

        fclose($fp);

        // Сгенерируйте URL для скачивания файла
        $fileUrl = asset("downloads/{$fileName}");

        return $fileUrl;
    }


}
