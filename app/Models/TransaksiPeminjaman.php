<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'transaksi_peminjaman';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $fillable = [
        'id_transaksi',
        'id_mobil',
        'id_customer',
        'id_driver',
        'id_pegawai',
        'kode_promo',
        'waktu_transaksi',
        'waktu_mulai',
        'waktu_selesai',
        'waktu_pengembalian',
        'subtotal_mobil',
        'subtotal_driver',
        'total_denda',
        'total_diskon',
        'grand_total',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_transaksi',
        'rating_driver',
        'review_driver'
    ];

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
