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
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom 'role' dengan tipe enum untuk membatasi nilai
            // Nilai default: 'customer' (karena sebagian besar user adalah customer)
            $table->enum('role', ['organizer', 'customer'])->default('customer')->after('password');
            
            // Menambah indeks untuk performa query berdasarkan role
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom dan indeks jika rollback
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
};