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
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Anggota yang meminjam
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade'); // Buku yang dipinjam
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_pengembalian')->nullable();
            $table->unsignedInteger('denda')->default(0);
            $table->string('status')->default('Dipinjam');
            $table->timestamps();

            $table->index('tanggal_peminjaman');
            $table->index('tanggal_jatuh_tempo');
            $table->index('status');
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
