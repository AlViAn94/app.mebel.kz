<?php

namespace App\Http\Controllers\v1\Order;

use App\Services\v1\Order\CreatedOrderService;
use App\Services\v1\Order\DeletedOrderService;
use App\Http\Requests\v1\Other\iDRequest;
use App\Http\Controllers\Controller;
use App\Services\v1\Order\SortOrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\v1\Client;
use App\Models\v1\Order;

class OrderController extends Controller
{
    public function index(Request $request, SortOrderService $service)
    {
        return $service->getOrdersService($request->all());
    }

    public function create(Request $request, CreatedOrderService $service)
    {
        return $service->createOrder($request->all());
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $result = [];
        $order = Order::where('id', $id)->first();
        $client = Client::where('id', $order['client_id'])->first();
        $result['order'] = $order;
        $result['client'] = $client;
        return $result;

    }

    public function update(Request $request)
    {
        return Order::updateOrder($request->all());
    }

    public function destroy(iDRequest $request, DeletedOrderService $service)
    {
        return $service->deletedOrder($request->id);
    }



    // tests for mds project
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
