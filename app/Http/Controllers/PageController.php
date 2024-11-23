<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Category;
use App\Models\PageType;
use App\Models\Version;
use Illuminate\Http\Request;
use Str;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Display list of all pages
     */
    public function all(Request $request)
    {
        $pages = Page::orderBy("title")
            ->get()
            ->groupBy(function (Page $page) {
                return strtoupper(substr($page->title, 0, 1));
            });

        return view("page.all", ["pages" => $pages]);
    }

    /**
     * Display a single page.
     */
    public function single(Request $request, string $slug)
    {
        $page = Page::where("title", urldecode($slug))->first();

        if (!$page) {
            return redirect(route("page.create", ["title" => $slug]));
        }

        if ($page->redirectTo) {
            return redirect(
                route("page.view", ["slug" => $page->redirectTo->slug])
            );
        }

        $version = $page->currentVersion;
        if ($request->has("version")) {
            $version = Version::findOrFail($request->input("version"));
        }

        // Calculate age (based on last update)
        $today = Carbon::now();
        $age = $version->created_at->diffInDays($today);

        return view("page.single", [
            "page" => $page,
            "version" => $version,
            "ageColour" => $this->ageToColour($age),
        ]);
    }

    protected function ageToColour($age): string
    {
        $colour = "green";

        if ($age > 2) {
            $colour = "emerald";
        }
        if ($age > 4) {
            $colour = "teal";
        }
        if ($age > 8) {
            $colour = "cyan";
        }
        if ($age > 16) {
            $colour = "sky";
        }
        if ($age > 32) {
            $colour = "indigo";
        }
        if ($age > 64) {
            $colour = "purple";
        }
        if ($age > 128) {
            $colour = "fuchsia";
        }
        if ($age > 256) {
            $colour = "yellow";
        }
        if ($age > 512) {
            $colour = "orange";
        }
        if ($age > 1024) {
            $colour = "rose";
        }

        return $colour;
    }

    /**
     * View a page's history.
     */
    public function history(string $slug)
    {
        $page = Page::where("title", urldecode($slug))->firstOrFail();
        return view("page.history", ["page" => $page]);
    }

    /**
     * Display the form to create a new page
     */
    public function create(Request $request)
    {
        if ($request->has("title")) {
            return view("page.create", [
                "types" => PageType::all(),
                "title" => $request->get("title"),
            ]);
        }
        return view("page.create", ["types" => PageType::all()]);
    }

    /**
     * Create a new page in the database
     */
    public function save(Request $request)
    {
        $input = $request->validate([
            "title" => ["required", "unique:" . Page::class],
            "type" => ["required", "exists:" . PageType::class . ",id"],
        ]);

        $page = new Page();
        $page->title = $input["title"];
        $page->is_locked = false;
        $page->user()->associate(auth()->user());

        // We already validate that the type ID exists so we don't need to handle it here
        $type = PageType::find($input["type"]);
        $page->type()->associate($type);
        $page->save();

        $page->createVersion($type->template ?? "");

        return view("page.edit", ["page" => $page]);
    }

    public function edit(string $slug)
    {
        $page = Page::where("title", urldecode($slug))->firstOrFail();
        return view("page.edit", ["page" => $page]);
    }

    public function update(Request $request, string $slug)
    {
        $page = Page::where("title", urldecode($slug))->firstOrFail();

        $input = $request->validate(["content" => "required"]);

        // Save diff of new text
        if ($input["content"] != $page->currentVersion->content) {
            $page->createVersion($input["content"]);
        }

        // Save the change to recent changes
        $summary = $request->input("summary", null);
        $page->saveChange("updated", $summary);

        // Calculate links
        $page->calculateLinks($input["content"]);

        // Save categories
        $categories = $request->input("categories", []);
        $categoryIds = [];
        foreach ($categories as $category) {
            $existing = Category::where("title", Str::apa($category))->first();
            if ($existing) {
                $categoryIds[] = $existing->id;
                continue;
            }

            $new = Category::create([
                "title" => Str::apa($category),
                "user_id" => auth()->user()->id,
            ]);
            $new->save();
            $new->createVersion("");
            $categoryIds[] = $new->id;
        }
        $page->categories()->sync($categoryIds);

        return redirect(route("page.view", ["slug" => $page->slug]));
    }
}
