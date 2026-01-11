<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('method', 10)->nullable()->after('action');
            $table->string('url')->nullable()->after('method');
            $table->unsignedSmallInteger('status_code')->nullable()->after('user_agent');
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['method', 'url', 'status_code']);
        });
    }
};
