<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedTinyInteger('proficiency')->nullable(); // 0-100
            $table->string('level')->nullable();                    // SkillLevel enum
            $table->string('icon')->nullable();
            $table->decimal('years_experience', 3, 1)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['skill_category_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
