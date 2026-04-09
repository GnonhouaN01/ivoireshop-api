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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('label', 50)->default('Domicile'); // Domicile, Bureau...
            $table->string('full_name', 100);
            $table->string('phone', 20);
            $table->string('city', 100)->default('Abidjan');
            $table->string('commune', 100)->nullable(); // Cocody, Plateau, Yopougon...
            $table->string('quartier', 100)->nullable();
            $table->text('details')->nullable();          // Compléments d'adresse
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
