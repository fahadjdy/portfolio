<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client_name')->nullable();
            $table->string('category')->nullable();
            $table->string('summary', 500);
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->text('outcome')->nullable();
            $table->json('highlights')->nullable();
            $table->string('live_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->smallInteger('year')->nullable();
            $table->string('role')->nullable();
            $table->string('duration')->nullable();
            $table->string('status')->default('draft'); // ProjectStatus enum
            $table->boolean('is_featured')->default(false);
            $table->string('cover_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
            $table->string('og_image')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'position']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
