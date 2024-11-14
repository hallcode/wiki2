<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageType;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display list of all pages
     */
    public function all(Request $request)
    {
        $pages = Page::all();

        return view("page.all", ["pages" => $pages]);
    }

    /**
     * Display the form to create a new page
     */
    public function create(Request $request)
    {
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

        $page->createVersion($type->template);

        return view("page.edit", ["page" => $page]);
    }

    public function edit(Request $request, string $slug)
    {
        $page = Page::where("title", urldecode($slug))->firstOrFail();
        return view("page.edit", ["page" => $page]);
    }
}
