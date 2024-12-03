<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Page;
use App\Models\Event;
use App\Models\PageLink;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class SpecialController extends Controller
{
    public function dashboard()
    {
        // Get page count
        $pageCount = Page::where("redirect_to", null)
            ->where("is_template", false)
            ->count();

        // Get old pages
        $oldPages = Page::orderBy("updated_at", "asc")->limit(10)->get();

        // Get recent images
        $recentMedia = Media::orderBy("created_at", "desc")->limit(8)->get();

        // Get needed pages
        $neededPages = PageLink::select([
            "link_text",
            DB::raw("COUNT(*) as link_count"),
        ])
            ->where("target_exists", false)
            ->groupBy("link_text")
            ->orderBy("link_count", "desc")
            ->limit(10)
            ->get();

        // Get the on this days
        $todayMonthDay = now()->format("m-d");
        $events = Event::whereRaw("TO_CHAR(date, 'MM-DD') = ?", [
            $todayMonthDay,
        ])->get();
        return view("welcome", [
            "events" => $events,
            "pageCount" => $pageCount,
            "oldPages" => $oldPages,
            "neededPages" => $neededPages,
            "media" => $recentMedia,
        ]);
    }

    public function recentChanges()
    {
        $changes = Change::select([
            "type",
            "changeable_type",
            "changeable_id",
            "user_id",
            DB::raw("COUNT(*) as change_count"),
            DB::raw("max(created_at) as date"),
        ])
            ->where("created_at", ">=", now()->subDays(7))
            ->groupBy("type", "changeable_type", "changeable_id", "user_id")
            ->orderBy("date", "desc")
            ->get();

        return view("special.recent-changes", ["changes" => $changes]);
    }

    public function randomPage()
    {
        $page = Page::inRandomOrder()->first();
        return redirect(route("page.view", ["slug" => $page->slug]));
    }

    public function search(Request $request)
    {
        $term = urldecode($request->get("query", ""));

        $pageResults = Page::where("title", "ilike", "%{$term}%")
            ->limit(20)
            ->get();

        $mediaResults = Media::where("title", "ilike", "%{$term}%")
            ->limit(20)
            ->get();

        if ($request->has("full")) {
            return view("special.search", [
                "pages" => $pageResults,
                "media" => $mediaResults,
            ]);
        }

        return view("fragments.search", [
            "pages" => $pageResults,
            "media" => $mediaResults,
        ]);
    }

    public function users()
    {
        $users = User::all();

        return view("special.users", ["users" => $users]);
    }
}
