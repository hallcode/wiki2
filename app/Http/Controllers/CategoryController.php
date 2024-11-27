<?php

namespace App\Http\Controllers;

use Str;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function all()
    {
        $categories = Category::orderBy("title")->get();
        return view("category.all", ["categories" => $categories]);
    }

    public function view($slug)
    {
        $category = Category::where("title", urldecode($slug))->firstOrFail();
        return view("category.single", ["category" => $category]);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view("category.edit", ["category" => $category]);
    }

    public function save(Request $request, $id)
    {
        $input = $request->validate([
            "title" => ["required", "unique:" . Category::class],
            "content" => "required",
        ]);

        $category = Category::findOrFail($id);

        $category->title = Str::apa($input["title"]);
        $category->save();

        if ($input["content"] !== $category->currentVersion()->content) {
            $category->createVersion($input["content"]);
        }

        return redirect()->back();
    }
}
