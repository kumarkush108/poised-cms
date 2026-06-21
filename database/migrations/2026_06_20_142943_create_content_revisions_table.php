<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->morphs('revisionable');
            $table->json('snapshot');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('summary')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['revisionable_type', 'revisionable_id', 'created_at'], 'content_revisions_revisionable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
    }
};
