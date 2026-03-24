<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plate_ingredient', function (Blueprint $table) {
            $table->foreignId('plate_id')->constrained('plates')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->primary(['plate_id', 'ingredient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plate_ingredient');
    }
};
