<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_costs', function (Blueprint $table) {
            // 1. Hanya drop foreign key constraint jika benar-benar ada
            if ($this->foreignKeyExists('booking_costs', 'booking_costs_booking_id_foreign')) {
                $table->dropForeign('booking_costs_booking_id_foreign');
            }

            if ($this->foreignKeyExists('booking_costs', 'booking_costs_account_id_foreign')) {
                $table->dropForeign('booking_costs_account_id_foreign');
            }

            // 3. Ubah kolom date menjadi tidak nullable
            $table->date('date')->nullable(false)->change();

            // 4. Tambahkan constraint foreign key yang benar
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('cascade');

            $table->foreign('account_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('booking_costs', function (Blueprint $table) {
            // Hapus constraint yang baru ditambahkan
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['account_id']);

            // Kembalikan ke nullable
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Check if a foreign key exists (MySQL)
     */
    protected function foreignKeyExists(string $table, string $foreignKey): bool
    {
        $result = DB::select("
            SELECT COUNT(*) AS count
            FROM information_schema.table_constraints
            WHERE constraint_schema = DATABASE()
              AND table_name = ?
              AND constraint_name = ?
              AND constraint_type = 'FOREIGN KEY'
        ", [$table, $foreignKey]);

        return $result[0]->count > 0;
    }
};