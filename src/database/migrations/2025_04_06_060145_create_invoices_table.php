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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('biller_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('title');
            $table->string('serial_number')->nullable()->autoIncrement();
            $table->string('po_so_number')->nullable();
            $table->date('issue_date')->defult(now());
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->decimal('payable_amount', 15, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
