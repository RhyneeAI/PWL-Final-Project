<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('branch_settings', 'supplier_prefix')) {
            return;
        }

        Schema::table('branch_settings', function (Blueprint $table) {
            $table->string('supplier_prefix')->default('SUP')->after('transaction_prefix');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('branch_settings', 'supplier_prefix')) {
            return;
        }

        Schema::table('branch_settings', function (Blueprint $table) {
            $table->dropColumn('supplier_prefix');
        });
    }
};
