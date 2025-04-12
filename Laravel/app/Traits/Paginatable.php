<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

trait Paginatable
{
    public function paginateWithPerPage(Builder $query, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? request('perPage', 3);
        return $query->paginate($perPage);
    }
}