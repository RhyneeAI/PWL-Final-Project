<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pengaturan per-cabang: prefix kode barang, transaksi, pajak, dll.
     */
    public function up(): void
    {
        Schema::create('branch_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->unique()->constrained('branches')->cascadeOnDelete();
            $table->string('product_prefix')->default('PRD');   // prefix kode produk
            $table->string('transaction_prefix')->default('TRX'); // prefix kode transaksi
            $table->string('supplier_prefix')->default('SUP'); // prefix kode supplier
            $table->boolean('tax_enabled')->default(false);
            $table->decimal('tax_rate', 5, 2)->default(0.00);   // persentase pajak, e.g. 11.00
            $table->boolean('discount_enabled')->default(true);
            $table->string('currency_symbol')->default('Rp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_settings');
    }
};
