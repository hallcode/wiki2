<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Page;
use App\Models\Event;

class SpecialController extends Controller
{
    public function dashboard()
    {
        // Get page count
        $pageCount = Page::count();

        // Get old pages
        $oldPages = Page::orderBy("updated_at", "asc")->limit(10)->get();

        // Get the on this days
        $todayMonthDay = now()->format("m-d");
        $events = Event::whereRaw("TO_CHAR(date, 'MM-DD') = ?", [
            $todayMonthDay,
        ])->get();
        return view("welcome", [
            "events" => $events,
            "pageCount" => $pageCount,
            "oldPages" => $oldPages,
        ]);
    }

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

    public function randomPage()
    {
        $page = Page::inRandomOrder()->first();
        return redirect(route("page.view", ["slug" => $page->slug]));
    }
}
