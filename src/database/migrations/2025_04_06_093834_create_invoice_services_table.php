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
        Schema::create('invoice_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->integer('tax')->nullable()->default(0);
            $table->string('code')->nullable()->default('');
            $table->integer('unit')->nullable()->default(1);
            $table->decimal('amount', 15, 2)->nullable()->default(0);
            $table->decimal('price', 15, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_services');
    }
};
