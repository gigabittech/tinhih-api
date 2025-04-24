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
        Schema::create('calendar_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->enum('week_start', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->default('sunday');
            $table->boolean('show_weekends')->default(true);
            $table->enum('timeslot_size', ['small', 'medium', 'large', 'extra_large'])->default('medium');
            $table->enum('time_increment', ['5_minute','10_minute', '15_minute', '20_minute', '30_minute', '60_minute'])->default('15_minute');
            $table->enum('time_format', ['12_hr', '24_hr'])->default('12_hr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_settings');
    }
};
