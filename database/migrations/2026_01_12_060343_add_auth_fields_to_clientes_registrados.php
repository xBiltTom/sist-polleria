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
        Schema::table('clientes_registrados', function (Blueprint $table) {
            $table->string('emailCliente', 255)->unique()->nullable()->after('apellidoCliente');
            $table->string('password', 255)->nullable()->after('emailCliente');
            $table->rememberToken()->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('remember_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes_registrados', function (Blueprint $table) {
            $table->dropColumn(['emailCliente', 'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at']);
        });
    }
};
