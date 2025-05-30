<?php

namespace App\Http\Controllers\API\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HandlesLatest
{
    protected function applyLatest(Request $request, Builder $query): Builder
    {
        $count = $request->query('latest');

        return $query->latest()
            ->when(is_numeric($count) && $count > 0, fn ($q) => $q->take((int) $count));
    }
}
