<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    // Profile routes
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit"
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update"
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy"
    );

    // Page routes
    Route::get("/pages", [PageController::class, "all"])->name("page.all");
    Route::get("/pages/create", [PageController::class, "create"])->name(
        "page.create"
    );
    Route::post("/pages/create", [PageController::class, "save"])->name(
        "page.save"
    );
    Route::get("/pages/{slug}", [PageController::class, "single"])->name(
        "page.view"
    );
    Route::get("/pages/{slug}/edit", [PageController::class, "edit"])->name(
        "page.edit"
    );
});

require __DIR__ . "/auth.php";
