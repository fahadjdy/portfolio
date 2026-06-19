<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('scope')->default('global'); // global | service | project
            $table->unsignedBigInteger('scope_id')->nullable(); // service_id / project_id when scoped
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['scope', 'scope_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
