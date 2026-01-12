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
        Schema::table('insumo', function (Blueprint $table) {
            // URL de la imagen en Cloudinary (si no existe el campo, descomentar la siguiente línea)
            // $table->string('urlImagenInsumo', 500)->nullable();

            // ID público de Cloudinary para gestionar la imagen (máximo 255 caracteres)
            $table->string('idImagenInsumo', 255)->nullable()->after('imagenInsumo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumo', function (Blueprint $table) {
            $table->dropColumn('idImagenInsumo');
            // $table->dropColumn('urlImagenInsumo');
        });
    }
};
