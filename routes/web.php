<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecialController;
use App\Http\Controllers\PageTypeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\MediaController;

use Illuminate\Support\Facades\Route;

Route::middleware("auth", "verified")->group(function () {
    // Special Pages
    Route::controller(SpecialController::class)->group(function () {
        Route::get("/", "dashboard")->name("dashboard");
        Route::get("/users", "users")->name("users");
        Route::get("/recent-changes", "recentChanges")->name("recent-changes");
        Route::get("/random-page", "randomPage")->name("page.random");
        Route::get("/search", "search")->name("search");
    });

    // Files & Uploads
    Route::controller(UploadController::class)->group(function () {
        Route::get("/upload", "getBlankForm")->name("upload");
        Route::post("/upload", "store")->name("upload");
    });
    Route::prefix("media")
        ->controller(MediaController::class)
        ->group(function () {
            Route::get("/", "all")->name("media.all");
            Route::get("/{slug}", "single")->name("media.view");
            Route::get("/{slug}/width:{size}", "getThumbnail")->name(
                "media.thumb"
            );
            Route::get("/{slug}/edit", "edit")->name("media.edit");
            Route::post("/{slug}", "update")->name("media.update");
            Route::get("/file/{fileName}", "getFile")->name("file.view");
        });

    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get("/profile", "edit")->name("profile.edit");
        Route::patch("/profile", "update")->name("profile.update");
        Route::delete("/profile", "destroy")->name("profile.destroy");
        Route::put("/profile/page", "putPage")->name("profile.updatePage");
    });

    //*** Page routes ***
    Route::prefix("pages")
        ->controller(PageController::class)
        ->group(function () {
            Route::get("/", "all")->name("page.all");
            Route::get("/create", "create")->name("page.create");
            Route::post("/create", "save");
            Route::get("/{slug}", "single")->name("page.view");
            Route::get("/{slug}/edit", "edit")->name("page.edit");
            Route::post("/{slug}/edit", "update");
            Route::get("/{slug}/history", "history")->name("page.history");
            Route::get("/{slug}/data", "data")->name("page.data");
            Route::put("/{slug}/data", "putData");
            Route::delete("/{slug}/data/{id}", "deleteData")->name(
                "page.data.delete"
            );
            Route::put("/{slug}/version", "setVersion")->name(
                "page.setVersion"
            );
        });

    //*** Category routes ***
    Route::prefix("categories")
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get("/", "all")->name("cat.all");
            Route::get("/{slug}", "view")->name("cat.view");
            Route::get("/{id}/edit", "edit")->name("cat.edit");
            Route::post("/{id}/edit", "save")->name("cat.save");
        });

    //*** Standalone events/timelines ***
    Route::prefix("events")
        ->controller(EventController::class)
        ->group(function () {
            Route::delete("/{id}", "delete")->name("event.delete");
        });

    //*** Page Timelines ***
    Route::prefix("pages/{slug}/timeline")
        ->controller(EventController::class)
        ->group(function () {
            Route::get("/", "all")->name("page.timeline");
            Route::get("/{id}/edit", "edit")->name("page.timeline.edit");
            Route::get("/create", "edit")->name("page.timeline.create");
            Route::post("/", "save");
            Route::post("/{id}", "save")->name("page.timeline.save");
            Route::delete("/{id}", "save")->name("page.timeline.delete");
        });

    //*** Admin ***
    Route::prefix("admin")->group(function () {
        // Page Types
        Route::controller(PageTypeController::class)->group(function () {
            Route::get("/page-types", "getAll")->name("pageType.all");
            Route::get("/page-types/create", "edit")->name("pageType.create");
            Route::get("/page-types/{id?}", "edit")->name("pageType.edit");
            Route::post("/page-types/{id?}", "store");
            Route::delete("/page-types/{id}", "delete")->name(
                "pageType.delete"
            );
        });
    });
});

require __DIR__ . "/auth.php";
