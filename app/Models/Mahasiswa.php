<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nim',
        'nama',
        'jenis_kelamin',
        'agama_id',
        'prodi_id',
        'provinsi_id',
        'kabkota_id',
        'kec_id',
        'desa_id',
    ];

    public function agama()
    {
        return $this->belongsTo(Agama::class,'agama_id');
    }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class,'prodi_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class,'provinsi_id');
    }
    public function kabkota()
    {
        return $this->belongsTo(Kabkota::class,'kabkota_id');
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class,'kec_id');
    }
    public function desa()
    {
        return $this->belongsTo(Desa::class,'desa_id');
    }
}
