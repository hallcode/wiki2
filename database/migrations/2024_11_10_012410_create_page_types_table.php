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
        Schema::create("page_types", function (Blueprint $table) {
            $table->id();
            $table->string("title", 255)->unique();
            $table->string("colour", 25)->nullable()->default("blue");
            $table->text("description")->nullable();
            $table->text("template")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("page_types");
    }
};
