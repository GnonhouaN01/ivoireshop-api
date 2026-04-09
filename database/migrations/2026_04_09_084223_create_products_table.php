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
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 0);           // Prix en FCFA (pas de centimes)
            $table->decimal('compare_price', 10, 0)->nullable(); // Prix barré
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('sku', 100)->unique()->nullable(); // Code produit
            $table->json('images')->nullable();          // Tableau d'URLs d'images
            $table->json('attributes')->nullable();      // Couleur, taille, matière...
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Les index pour les recherches
            $table->index(['category_id','is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('price');
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
