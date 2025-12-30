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
            
            $table->foreignId('event_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            $table->string('name'); // Nama jenis tiket (VIP, Regular, VVIP, dll)
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Harga tiket
            $table->integer('quota'); // Total kuota tiket
            $table->integer('sold')->default(0); // Jumlah tiket yang sudah terjual
            $table->integer('available')->virtualAs('quota - sold'); // Tiket tersedia (kolom virtual)
            
            $table->dateTime('sale_start')->nullable(); // Waktu mulai penjualan
            $table->dateTime('sale_end')->nullable(); // Waktu berakhir penjualan
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
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
