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
            // Drop profile-related columns
            if (Schema::hasColumn('users', 'company_name')) {
                $table->dropColumn(['company_name', 'phone', 'website', 'bio', 'address']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('role');
            $table->string('phone')->nullable()->after('company_name');
            $table->string('website')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('website');
            $table->string('address')->nullable()->after('bio');
        });
    }
};
