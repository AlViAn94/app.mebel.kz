<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Design;
use App\Models\v1\Job;
use App\Models\v1\Metring;
use App\Models\v1\Technologist;
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
        $job = Job::find($id);
        $position = $request->position;

        switch ($position){
            case 'metrings':
                $model = Metring::where('id', $id)->first();
                if($model){
                    if($model['file'] != null){
                        $this->DeletedFileService->deletedFile($position, $id);
                        return Metring::updateCard($id);

                    }else{
                        return Metring::updateCard($id);
                    }
                }
                break;

            case 'design':
                $model = Design::where('id', $id)->first();
                if($model){
                    if($model['file'] != null){
                        $this->DeletedFileService->deletedFile($position, $id);
                        return Design::updateCard($id);

                    }else{
                        return Design::updateCard($id);
                    }
                }
                break;

            case 'technologists':
                $model = Technologist::where('id', $id)->first();
                if($model){
                    if($model['file'] != null){
                        $this->DeletedFileService->deletedFile($position, $id);
                        return Technologist::updateCard($id);
                    }else{
                        return Technologist::updateCard($id);
                    }
                }
                break;

            default:
                $job->update([
                    'user_id' => null,
                    'user_name' => null,
                    'take_date' => null,
                    'passed_date' => null,
                    'status' => 0
                    ]);
                return response()->json(['message' => 'Карта успешно сброшена!']);
        }

            return response()->json(['error' => 'Не удалось сбросить!'], 404);
    }
}
