<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm',
        'role',
        'id_poli',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function jadwalPeriksa()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }

    // Untuk pasien: daftar poli yang telah dilakukan
    public function daftarPolis()
    {
        return $this->hasMany(DaftarPoli::class, 'id_pasien');
    }

    // Untuk dokter: pasien yang telah diperiksa
    public function periksas()
    {
        return $this->hasManyThrough(Periksa::class, DaftarPoli::class, 'id_pasien', 'id_daftar_poli');
    }
}
