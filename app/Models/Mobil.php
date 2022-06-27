<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $table = 'mobils';
    protected $primaryKey = 'id_mobil';
    public $incrementing = false;
    protected $fillable = [
        'id_mobil',
        'id_mitra',
        'plat_mobil',
        'no_stnk',
        'nama_mobil',
        'tipe_mobil',
        'jenis_aset',
        'jenis_transmisi',
        'jenis_bahan_bakar',
        'volume_bahan_bakar',
        'warna_mobil',
        'fasilitas_mobil',
        'volume_bagasi',
        'kapasitas_penumpang',
        'harga_sewa',
        'servis_terakhir',
        'foto_mobil',
        'periode_mulai',
        'periode_selesai'
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
