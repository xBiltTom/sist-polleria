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
        Schema::create('route_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique();
            $table->string('permission_name');
            $table->unsignedBigInteger('module_id')->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('permission_modules')->onDelete('set null');
            $table->index('permission_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_permissions');
    }
};
