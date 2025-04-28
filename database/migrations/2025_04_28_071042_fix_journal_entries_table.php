<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // 1. Hapus foreign key constraint sementara
            $table->dropForeign(['journal_id']);
            $table->dropForeign(['account_id']);
            $table->dropForeign(['booking_id']);

            // 2. Ubah kolom ke unsignedBigInteger
            $table->unsignedBigInteger('journal_id')->change();
            $table->unsignedBigInteger('account_id')->change();
            $table->unsignedBigInteger('booking_id')->nullable()->change();

            // 3. Tambahkan kembali foreign key dengan constraint baru
            $table->foreign('journal_id')
                  ->references('id')
                  ->on('journals')
                  ->onDelete('cascade');

            $table->foreign('account_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('cascade');

            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // Kembalikan ke keadaan semula
            $table->dropForeign(['journal_id']);
            $table->dropForeign(['account_id']);
            $table->dropForeign(['booking_id']);

            $table->foreignId('journal_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('account_id')->constrained()->change();
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade')->change();
        });
    }
};
