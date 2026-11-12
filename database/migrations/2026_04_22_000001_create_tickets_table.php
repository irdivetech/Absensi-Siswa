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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id'); // FK to users (siswa)
            $table->unsignedBigInteger('operator_id')->nullable(); // FK to users (admin)
            $table->string('subject');
            $table->text('description');
            $table->string('category'); 
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->timestamp('first_response_at')->nullable(); 
            $table->timestamp('resolved_at')->nullable(); 
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('reporter_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('operator_id')->references('id_user')->on('users')->onDelete('set null');

            // Indexing untuk pencarian cepat
            $table->index('subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
