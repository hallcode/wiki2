<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display all events for a particular page
     */
    public function all(Request $request, string $slug)
    {
        $page = Page::where("title", urldecode($slug))->firstOrFail();
        return view("page.timeline.view", ["page" => $page]);
    }

    /**
     * Display the edit form; no single view so if there's an ID,
     * show the edit form, if not then show a blank form.
     */
    public function edit(Request $request, string $slug = null, $id = null)
    {
        if (!empty($slug)) {
            $page = Page::where("title", urldecode($slug))->firstOrFail();
        }

        if (!empty($id)) {
            $event = Event::findOrFail($id);
        }

        return view("page.timeline.edit", [
            "page" => $page ?? null,
            "event" => $event ?? null,
        ]);
    }

    /**
     * Save the event
     */
    public function save(Request $request, string $slug = null, $id = null)
    {
        $input = $request->validate([
            "title" => "required",
            "date" => "required",
            "month" => "required",
            "year" => "required",
            "content" => "required",
        ]);

        $date = Carbon::createFromDate(
            $input["year"],
            $input["month"],
            $input["date"]
        );

        if (!empty($slug)) {
            $page = Page::where("title", urldecode($slug))->firstOrFail();
        }

        if (!empty($id)) {
            $event = Event::findOrFail($id);
            $event->title = $input["title"];
            $event->date = $date;
            $event->save();
        }

        if (!isset($event)) {
            $event = Event::create([
                "title" => $input["title"],
                "date" => $date,
                "user_id" => auth()->user()->id,
            ]);
        }

        if (isset($page)) {
            $event->page_id = $page->id;
            $event->save();
        }

        $event->createVersion($input["content"]);

        return redirect(route("page.timeline", ["slug" => $page->slug]));
    }

    /**
     * Archive the event
     */
    public function delete(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->back();
    }
}
