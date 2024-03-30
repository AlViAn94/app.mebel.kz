<?php

namespace App\Models\v1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Store extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'store';

    protected $casts = [
        'position' => 'array',
    ];

    protected $fillable = [
        'position',
        'total'
    ];

    public static function ticket($data): JsonResponse
    {
        $positions = $data['position'];

        foreach ($positions as $position)
        {
            $id = $position['id'];
            $count = $position['count'];

            // Выполняем запрос на обновление данных
            $result = Sklad::query()
                ->where('id', $id)
                ->where('count', '>=', $count)
                ->decrement('count', $count);
            if($result == 0){
                return response()->json(['success' => false, 'message' => 'Не достаточно товара: ' . $position['position']]);
            }
        }
        // Сохраняем чек
        self::query()->create([
            'position' => $data['position'],
            'total'    => $data['total']
        ]);
        return response()->json(['success' => true, 'message' => 'Покупка произведена успешно']);
    }

    public static function list($data): LengthAwarePaginator|JsonResponse
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);

        $positions = [
            'dir',
            'manager',
            'admin',
            'store'
        ];

        if (empty(array_intersect($positions, $roles))) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }

        $search = $data['search'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $count = $data['count'];
        $page = $data['page'];

        if (empty($sort)) {
            $sort = 'created_at';
        }

        return self::query()->where(function ($query) use ($search) {
            $query->where('position', 'LIKE', "%{$search}%");
        })
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($count, ['*'], 'page', $page);
    }
}
