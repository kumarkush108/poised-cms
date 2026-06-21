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
        Schema::create('content_media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('role', 20)->default('gallery');
            $table->string('caption')->nullable();
            $table->unsignedInteger('order_column')->default(0);
            $table->timestamps();

            $table->index(['mediable_type', 'mediable_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_media');
    }
};
