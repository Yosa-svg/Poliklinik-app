<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    protected $table = 'periksa';
    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa',
        'bukti_bayar',
        'status_bayar',
    ];

    protected $casts = [
        'tgl_periksa' => 'datetime',
    ];

    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }

    /**
     * Get total biaya including medicines
     */
    public function getTotalBiayaAttribute(): int
    {
        $biayaObat = $this->detailPeriksa->sum(fn($d) => $d->obat?->harga ?? 0);
        return ($this->biaya_periksa ?? 0) + $biayaObat;
    }

    /**
     * Status label helper
     */
    public function getStatusBayarLabelAttribute(): string
    {
        return match ($this->status_bayar) {
            'belum_bayar'          => 'Belum Bayar',
            'menunggu_verifikasi'  => 'Menunggu Verifikasi',
            'lunas'                => 'Lunas',
            default                => 'Tidak Diketahui',
        };
    }
}
