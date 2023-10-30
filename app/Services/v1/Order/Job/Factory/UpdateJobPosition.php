<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Design;
use App\Models\v1\Job;
use App\Models\v1\Metring;
use App\Models\v1\Technologist;
use App\Models\v1\Order;
use App\Services\v1\File\DeletedFileService;

class UpdateJobPosition
{

    protected $DeletedFileService;

    public function __construct(DeletedFileService $DeletedFileService)
    {
        $this->DeletedFileService = $DeletedFileService;
    }

    public function updatePosition($request, $id)
    {
        $position = $request->position;

        switch ($position){
            case 'metrings':
                $model = Metring::where('id', $id)->first();
                if($model){
                        $result = Order::dropOrder($model['order_id'], $position);
                        if($result){
                            return Metring::updateCard($id);
                        }else{
                            return response()->json(['message' => 'Не удалось отменить.'], 404);
                        }
                }
                break;

            case 'design':
                $model = Design::where('id', $id)->first();
                if($model){
                    $result = Order::dropOrder($model['order_id'], $position);
                    if($result){
                        return Metring::updateCard($id);
                    }else{
                        return response()->json(['message' => 'Не удалось отменить.'], 404);
                    }
                }
                break;

            case 'technologists':
                $model = Technologist::where('id', $id)->first();
                if($model){
                    $result = Order::dropOrder($model['order_id'], $position);
                    if($result){
                        return Metring::updateCard($id);
                    }else{
                        return response()->json(['message' => 'Не удалось отменить.'], 404);
                    }
                }
                break;

            default:
                $job = Job::find($id);
                if($job){
                    $job->update([
                        'user_id' => null,
                        'user_name' => null,
                        'take_date' => null,
                        'passed_date' => null,
                        'status' => 0
                    ]);
                }else{
                    return response()->json(['message' => 'Карта не найдена.'], 404);
                }

                return response()->json(['message' => 'Карта успешно сброшена!']);
        }

            return response()->json(['message' => 'Не удалось сбросить.'], 404);
    }
}
