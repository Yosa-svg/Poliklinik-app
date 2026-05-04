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
        Schema::table('periksa', function (Blueprint $table) {
            $table->string('bukti_bayar')->nullable()->after('biaya_periksa');
            $table->enum('status_bayar', ['belum_bayar', 'menunggu_verifikasi', 'lunas'])
                  ->default('belum_bayar')
                  ->after('bukti_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->dropColumn(['bukti_bayar', 'status_bayar']);
        });
    }
};
