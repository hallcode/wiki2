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
        Schema::table("media", function (Blueprint $table) {
            $table->dropColumn([
                "mime_type",
                "directory",
                "size",
                "filename",
                "user_id",
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("media", function (Blueprint $table) {
            $table->string("mime_type", 30);
            $table->string("directory");
            $table->integer("size")->nullable();
            $table->string("filename")->unique();
            $table->foreignId("user_id");
        });
    }
};
