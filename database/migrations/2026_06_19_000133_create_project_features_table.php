<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete(); // denormalized convenience
            $table->string('title');
            $table->string('description', 500)->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['project_panel_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_features');
    }
};
