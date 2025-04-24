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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('billingAddress')->nullable();
            $table->string('businessName')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('profession')->nullable();
            $table->enum('teamSize', ['justMe', 'inTen', 'moreThanTen'])->nullable();
            $table->string('website')->nullable();
            $table->string('timeZone')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
