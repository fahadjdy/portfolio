<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->string('institution');
            $table->string('location')->nullable();
            $table->smallInteger('start_year');
            $table->smallInteger('end_year')->nullable();
            $table->string('grade')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
