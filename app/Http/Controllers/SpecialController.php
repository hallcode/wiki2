<?php

namespace App\Http\Controllers;

use App\Models\Change;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialController extends Controller
{
    public function recentChanges()
    {
        $changes = Change::orderBy("created_at", "desc")
            ->limit(50)
            ->get()
            ->groupBy(function (Change $change) {
                return $change->created_at->format("l jS \\of F Y");
            });
        return view("special.recent-changes", ["changes" => $changes]);
    }
}
