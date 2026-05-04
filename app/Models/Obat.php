<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
        'stok',
    ];

    /**
     * Check if stock is low (≤ 5)
     */
    public function isLowStock(): bool
    {
        return $this->stok <= 5;
    }

    /**
     * Check if out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->stok <= 0;
    }

    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}
