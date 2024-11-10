<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("categories", function (Blueprint $table) {
            $table->id();
            $table->string("title", 255)->unique();
            $table->foreignId("user_id");
            $table->foreignUlid("version_id")->nullable();
            $table->softDeletes();
        });

        Schema::create("category_page", function (Blueprint $table) {
            $table->integer("category_id");
            $table->integer("page_id");

            $table->primary(["category_id", "page_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("categories");
    }
};
