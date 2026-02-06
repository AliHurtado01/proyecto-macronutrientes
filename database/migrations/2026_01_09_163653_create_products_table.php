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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Null = Global
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('bedca_id')->nullable(); // Para evitar duplicados al importar

            // Nutrientes
            $table->decimal('calories', 8, 2)->default(0);
            $table->decimal('energy_kj', 8, 2)->default(0);
            $table->decimal('protein', 8, 2)->default(0);
            $table->decimal('total_fat', 8, 2)->default(0);
            $table->decimal('carbohydrates', 8, 2)->default(0);
            $table->decimal('fiber', 8, 2)->default(0);
            $table->decimal('saturated_fat', 8, 2)->default(0);
            $table->decimal('monounsaturated_fat', 8, 2)->default(0);
            $table->decimal('polyunsaturated_fat', 8, 2)->default(0);
            $table->decimal('trans_fat', 8, 2)->default(0);
            $table->decimal('cholesterol', 8, 2)->default(0);
            $table->decimal('water', 8, 2)->default(0);

            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
