<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurrence_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_je_id')->constrained('journal_entries')->onDelete('cascade');
            $table->enum('frequency', ['Monthly', 'Quarterly', 'Yearly']);
            $table->date('next_run_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurrence_patterns');
    }
};
