<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Application extends Model
{
    use HasFactory, UsesTenantConnection;

    /*
     * Класс реализует методы по работе с заявками на перезвон
     */

    const TODAY = 'today';
    const WEEK = 'week';
    const MONTH = 'month';
    const ALL_TIME = 'all_time';

    protected $fillable = [
        'name',
        'phone',
        'is_active'
    ];

    // Подача заявки
    public static function addApplication($data): Model|Builder|JsonResponse
    {
        $existingApplication = self::query()->where('phone', $data['phone'])
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if($existingApplication){
            return response()->json(['message' => 'Вы уже оставляли заявку сегодня.']);
        }

        self::query()->where('phone', $data['phone'])
            ->whereDate('created_at', '<', Carbon::yesterday())
            ->update(['is_active' => 0]);

        return self::query()->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'is_active' => 1
        ]);
    }

    // Получение списка за период
    public static function getApplicationsByPeriod($data): Collection|array
    {
        switch ($data['period']) {
            case self::TODAY:
                $result = self::query()->whereDate('created_at', now()->toDateString())
                    ->where('is_active', '>=', 1)->get();

                if($result){
                    return $result;
                }else{
                    return [];
                }
            case self::WEEK:
                $result = self::query()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->where('is_active', '>=', 1)->get();

                if($result){
                    return $result;
                }else{
                    return [];
                }
            case self::MONTH:
                $result = self::query()->whereMonth('created_at', now()->month)
                    ->where('is_active', '>=', 1)->get();

                if($result){
                    return $result;
                }else{
                    return [];
                }
            case self::ALL_TIME:
                $result = self::all()->where('is_active', '>=', 1);

                if($result){
                    return $result;
                }else{
                    return [];
                }
            default:
                throw new \InvalidArgumentException('Invalid period provided');
        }
    }
}
