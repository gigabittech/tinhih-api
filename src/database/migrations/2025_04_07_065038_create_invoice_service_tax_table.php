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
        Schema::create('invoice_service_tax', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('invoice_services', 'id')->onDelete('cascade');
            $table->foreignId('tax_id')->constrained('taxes', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_service_tax');
    }
};
