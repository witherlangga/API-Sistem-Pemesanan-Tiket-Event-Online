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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            $table->string('transaction_code')->unique(); // Kode unik transaksi
            
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            $table->foreignId('event_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            $table->foreignId('ticket_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            $table->integer('quantity'); // Jumlah tiket yang dibeli
            $table->decimal('price_per_ticket', 10, 2); // Harga per tiket saat transaksi
            $table->decimal('subtotal', 10, 2); // Subtotal (quantity * price_per_ticket)
            $table->decimal('admin_fee', 10, 2)->default(0); // Biaya admin
            $table->decimal('total_amount', 10, 2); // Total yang harus dibayar
            
            $table->enum('status', ['pending', 'paid', 'cancelled', 'expired'])
                  ->default('pending');
            
            $table->enum('payment_method', ['bank_transfer', 'e_wallet', 'credit_card'])
                  ->nullable();
            
            $table->text('payment_proof')->nullable(); // Path file bukti pembayaran
            $table->dateTime('paid_at')->nullable(); // Waktu pembayaran
            $table->dateTime('expired_at')->nullable(); // Waktu kadaluarsa pembayaran
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
