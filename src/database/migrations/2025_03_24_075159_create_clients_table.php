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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('status');
            $table->string('phone')->nullable();
            $table->string('in')->nullable();
            $table->string('dob')->nullable();
            $table->string('sex')->nullable();
            $table->string('relationship')->nullable();
            $table->string('emp_status')->nullable();
            $table->string('ethnicity')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
