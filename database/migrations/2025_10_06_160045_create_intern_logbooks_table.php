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
        Schema::create('intern_logbooks', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key: Menghubungkan logbook ke user (Student)
            // Penting: Pastikan tabel 'users' sudah ada!
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Data Logbook
            $table->date('date');    
            $table->string('title')->nullable(); 
            $table->text('activity'); 
            
            // Status Review (Untuk Admin)
            $table->boolean('is_approved')->default(false); 

            // created_at (Kapan dibuat) dan updated_at (Kapan diupdate)
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_logbooks');
    }
};