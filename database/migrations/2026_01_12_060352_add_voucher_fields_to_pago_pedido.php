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
        Schema::table('pago_pedido', function (Blueprint $table) {
            $table->string('voucherUrl', 500)->nullable()->after('nroOperacion');
            $table->string('voucherPublicId', 255)->nullable()->after('voucherUrl');
            $table->enum('estadoValidacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente')->after('voucherPublicId');
            $table->text('motivoRechazo')->nullable()->after('estadoValidacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_pedido', function (Blueprint $table) {
            $table->dropColumn(['voucherUrl', 'voucherPublicId', 'estadoValidacion', 'motivoRechazo']);
        });
    }
};
