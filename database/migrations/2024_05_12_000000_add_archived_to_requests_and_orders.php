<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('rejection_reason');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('notes');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
        });
    }

    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at']);
        });
    }
}; 