<?php

namespace App\Http\Controllers;

use App\Models\PageType;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class PageTypeController extends Controller
{
    public function getAll()
    {
        $pageTypes = PageType::orderBy("title")->get();

        return view("admin.page-types", ["pageTypes" => $pageTypes]);
    }

    /*
     * There is no single view on this controller, just show the edit form with the pre-filled data.
     * Also, no special create page, just show a blank edit form.
     */
    public function edit(Request $request, $id = null)
    {
        if ($id == null) {
            // We're creating a new one so just show a blank form
            return view("admin.edit-page-type", [
                "pageTitle" => "Create page type",
            ]);
        }

        $pt = PageType::findOrFail($id);
        return view("admin.edit-page-type", [
            "pageTitle" => "Edit page type: " . $pt->title,
            "pageType" => $pt,
        ]);
    }

    public function store(Request $request, $id = null)
    {
        if ($id == null) {
            // Create new
            $this->createNew($request);
            return redirect(route("pageType.all"));
        }

        // Save existing
        $this->saveExisting($request, $id);
        return redirect(route("pageType.all"));
    }

    public function delete(Request $request, $id)
    {
        $pageType = PageType::findOrFail($id);

        if ($pageType->pages()->count() > 0) {
            abort(403);
        }

        $pageType->delete();
        return redirect(route("pageType.all"));
    }

    private function createNew(Request $request)
    {
        $input = $request->validate([
            "title" => ["required", "unique:page_types", "max:255"],
            "colour" => ["required", "max:25"],
            "description" => "nullable",
            "template" => "nullable",
        ]);

        $pt = new PageType();
        $pt->title = $input["title"];
        $pt->colour = $input["colour"];
        $pt->description = $input["description"];
        $pt->template = $input["template"];
        $pt->save();
    }

    private function saveExisting(Request $request, $id)
    {
        $input = $request->validate([
            "title" => [
                "required",
                Rule::unique("page_types")->ignore($id),
                "max:255",
            ],
            "colour" => ["required", "max:25"],
            "description" => "nullable",
            "template" => "nullable",
        ]);

        $pt = PageType::findOrFail($id);
        $pt->title = $input["title"];
        $pt->colour = $input["colour"];
        $pt->description = $request->get("description", $pt->description);
        $pt->template = $request->get("template", $pt->template);
        $pt->save();
    }
}
