<?php

namespace App\Services\v1\Order;

use App\Models\v1\Client;
use App\Models\v1\Order;

class SortOrderService
{
    public function getOrdersService($data)
    {
        $search = $data['search'];
        $status = $data['status'];
        $page = $data['page'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $pageSize = $data['count'];
        $position = $data['position'];
        $position_status = $data['position_status'];

        if(!$page){
            $page = 1;
        }

        if (empty($sort)) {
            $sort = 'updated_at';
        }
        $firstItemNumber = ($page - 1) * $pageSize + 1;

        $orders = Order::where(function ($query) use ($search, $status, $sort, $asc, $position_status, $position) {
            $query
                ->where('status', $status)
                ->where($position, $position_status)
                ->where(function ($query) use ($search) {
                    $query
                        ->where('order_num', 'LIKE', "%{$search}%")
                        ->orWhere('address', 'LIKE', "%{$search}%");
                });
        })
            ->orderBy('status', 'asc')
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($pageSize, ['*'], 'page', $page);
        $i = 0;
        foreach ($orders as $order) {
            $client = Client::where('id', $orders[$i]['client_id'])->select('name', 'surname', 'lastname')->first();
            $full_name = $client['surname'] . ' ' . $client['name'] . ' ' . $client['lastname'];
            $order->order_number = $firstItemNumber++;
            $order->full_name = $full_name;
            $i++;
        }
        return $orders;
    }
}
