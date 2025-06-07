<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\Golfer;
use Illuminate\Http\Request;

class GolferController extends Controller
{
    public function index(Request $request)
    {
        $q = Golfer::with('orderItem.order')
            ->wherePaid()
            ->orderBy('created_at');

        if ($request->query('group_by') === 'year') {
            // fetch and group
            return $q->get()
                ->groupBy(fn ($g) => $g->created_at->format('Y'))
                ->map(fn ($group) => $group->values())
                ->toArray();
        }

        // defaults to a flat list
        return $q->get();
    }
}
