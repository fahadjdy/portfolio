<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page');           // home, about
            $table->string('section_key');    // hero, about_intro, cta
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->json('data')->nullable(); // stats[], cta buttons, rotating titles, etc.
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['page', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
