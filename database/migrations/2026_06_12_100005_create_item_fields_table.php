<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_item_id')->constrained('section_items')->cascadeOnDelete();
            $table->string('field_key', 100);
            $table->longText('value')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->timestamps();

            $table->unique(['section_item_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_fields');
    }
};
