<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pencatatan semua pergerakan stok:
     *   ADJUST_IN  - penambahan stok manual (misal: pembelian dari supplier)
     *   ADJUST_OUT - pengurangan stok manual (misal: barang rusak/hilang)
     *   OPNAME     - stock opname (koreksi stok, bisa + atau -)
     *   SALES_OUT  - pengurangan stok otomatis dari transaksi penjualan
     */
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // user yang melakukan mutasi
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete(); // hanya untuk SALES_OUT
            $table->string('reference_code')->nullable(); // nomor referensi dokumen (PO, dll)
            $table->enum('type', ['ADJUST_IN', 'ADJUST_OUT', 'OPNAME', 'SALES_OUT']);
            $table->integer('quantity_before');  // stok sebelum mutasi
            $table->integer('quantity_change');  // positif = masuk, negatif = keluar
            $table->integer('quantity_after');   // stok sesudah mutasi
            $table->text('notes')->nullable();
            $table->timestamp('mutation_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
