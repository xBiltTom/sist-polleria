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
        Schema::table('empleado', function (Blueprint $table) {
            // ID público de Cloudinary para gestionar la imagen (máximo 255 caracteres)
            $table->string('idFotoEmpleado', 255)->nullable()->after('urlFotoEmpleado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleado', function (Blueprint $table) {
            $table->dropColumn('idFotoEmpleado');
        });
    }
};
