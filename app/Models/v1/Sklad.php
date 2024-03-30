<?php

namespace App\Models\v1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Sklad extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'sklad';

    protected $fillable = [
        'position',
        'count',
        'unit',
        'price',
        'code'
    ];

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
            $query->orWhere('code', 'LIKE', "%{$search}%");
        })
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($count, ['*'], 'page', $page);
    }
}
