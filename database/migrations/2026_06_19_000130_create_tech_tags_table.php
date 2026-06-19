<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tech_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('category')->nullable();  // TechCategory enum
            $table->boolean('is_featured')->default(false); // show in public Tech-Stack section
            $table->unsignedTinyInteger('proficiency')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_featured', 'position']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tech_tags');
    }
};
