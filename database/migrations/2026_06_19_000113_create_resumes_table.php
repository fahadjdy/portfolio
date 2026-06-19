<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->string('label')->default('Resume');
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->boolean('is_current')->default(false);
            $table->unsignedInteger('downloads')->default(0);
            $table->timestamps();

            $table->index('is_current');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
