<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('disk')->default('public');
            $table->string('path');                 // original / largest
            $table->string('path_webp')->nullable();
            $table->string('path_thumb')->nullable();
            $table->string('path_md')->nullable();
            $table->json('srcset')->nullable();      // { width: path }
            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->string('type')->default('screenshot'); // screenshot|mockup|logo|cover
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_images');
    }
};
