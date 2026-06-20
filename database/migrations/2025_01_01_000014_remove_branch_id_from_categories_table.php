<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'branch_id')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['branch_id', 'name']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('categories', 'branch_id')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('id')->constrained('branches')->cascadeOnDelete();
            $table->unique(['branch_id', 'name']);
        });
    }
};
