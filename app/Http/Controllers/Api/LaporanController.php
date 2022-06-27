<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    //laporan penyewaan mobil pada bulan tertentu
    public function penyewaanMobil($tahun, $bulan)
    {

        $reportMobil = DB::table('transaksi_peminjaman')
            ->select([
                'mobils.tipe_mobil as tipe_mobil',
                'mobils.nama_mobil as nama_mobil',
                DB::raw('count(id_transaksi) as jumlah_peminjaman'),
                DB::raw('sum(grand_total) as pendapatan'),
                DB::raw('month(waktu_transaksi) as month'),
                DB::raw('year(waktu_transaksi) as year'),

            ])
            ->join('mobils', 'mobils.id_mobil', '=', 'transaksi_peminjaman.id_mobil')
            ->where(DB::raw('month(waktu_transaksi)'), '=', $bulan)
            ->where(DB::raw('year(waktu_transaksi)'), '=', $tahun)
            ->groupBy(['tipe_mobil', 'nama_mobil', 'month', 'year'])
            ->orderBy('jumlah_peminjaman', 'desc')
            ->get();

        if (count($reportMobil) > 0) {
            return response([
                'message' => 'Generate Report Success',
                'data' => $reportMobil
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    //laporan detail pendapatan pada bulan tertentu
    public function detailPendapatan($tahun, $bulan)
    {

        $detailPendapatan = DB::table('transaksi_peminjaman')
            ->select([
                'customers.name as nama_customer',
                'mobils.nama_mobil as nama_mobil',
                DB::raw('(CASE WHEN (transaksi_peminjaman.id_driver IS NOT NULL) THEN "Peminjaman Mobil + Driver" ELSE "Peminjaman Mobil" END) as jenis_transaksi'),
                DB::raw('count(id_transaksi) as jumlah_transaksi'),
                DB::raw('sum(grand_total) as pendapatan'),
                DB::raw('month(waktu_transaksi) as month'),
                DB::raw('year(waktu_transaksi) as year')

            ])
            ->join('mobils', 'mobils.id_mobil', '=', 'transaksi_peminjaman.id_mobil')
            ->join('customers', 'customers.id', '=', 'transaksi_peminjaman.id_customer')
            ->where(DB::raw('month(waktu_transaksi)'), '=', $bulan)
            ->where(DB::raw('year(waktu_transaksi)'), '=', $tahun)
            ->groupBy(['nama_customer', 'nama_mobil', 'month', 'year', 'jenis_transaksi'])
//            ->orderBy('jumlah_peminjaman', 'desc')
            ->get();

        if (count($detailPendapatan) > 0) {
            return response([
                'message' => 'Generate Report Success',
                'data' => $detailPendapatan
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    //laporan 5 driver dengan pendapatan terbanyak pada bulan tertentu
    public function driverTransaksiTerbanyak($tahun, $bulan)
    {

        $topDriver = DB::table('transaksi_peminjaman')
            ->select([
                'drivers.id as id_driver',
                'drivers.name as nama_driver',
                DB::raw('count(id_transaksi) as jumlah_transaksi'),
                DB::raw('month(waktu_transaksi) as month'),
                DB::raw('year(waktu_transaksi) as year')

            ])
            ->join('drivers', 'drivers.id', '=', 'transaksi_peminjaman.id_driver')
            ->where(DB::raw('month(waktu_transaksi)'), '=', $bulan)
            ->where(DB::raw('year(waktu_transaksi)'), '=', $tahun)
            ->groupBy(['drivers.id', 'nama_driver', 'month', 'year'])
            ->orderBy('jumlah_transaksi', 'desc')
            ->take(5)
            ->get();

        if (count($topDriver) > 0) {
            return response([
                'message' => 'Generate Report Success',
                'data' => $topDriver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    //laporan performa driver
    public function driverPerforma($tahun, $bulan)
    {

        $topDriver = DB::table('drivers')
            ->select([
                'drivers.id as id_driver',
                'drivers.name as nama_driver',
                DB::raw('count(id_transaksi) as jumlah_transaksi'),
//                DB::raw('(sum(rating_driver) / count(rating_driver)) as rerata_rating'),
                DB::raw('avg(rating_driver) as rerata_rating'),
                DB::raw('month(waktu_transaksi) as month'),
                DB::raw('year(waktu_transaksi) as year')

            ])
            ->leftJoin('transaksi_peminjaman', 'drivers.id', '=', 'transaksi_peminjaman.id_driver')
            ->where(DB::raw('month(waktu_transaksi)'), '=', $bulan)
            ->where(DB::raw('year(waktu_transaksi)'), '=', $tahun)
            ->groupBy(['drivers.id', 'nama_driver', 'month', 'year'])
            ->orderBy('jumlah_transaksi', 'desc')
            ->get();

        if (count($topDriver) > 0) {
            return response([
                'message' => 'Generate Report Success',
                'data' => $topDriver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    //laporan 5 customer denga transaksi terbanyak pada bulan tertentu
    public function customerTransaksiTerbanyak($tahun, $bulan)
    {

        $topDriver = DB::table('transaksi_peminjaman')
            ->select([
                'customers.name as nama_customer',
                DB::raw('count(id_transaksi) as jumlah_transaksi'),
                DB::raw('month(waktu_transaksi) as month'),
                DB::raw('year(waktu_transaksi) as year')

            ])
            ->join('customers', 'customers.id', '=', 'transaksi_peminjaman.id_customer')
            ->where(DB::raw('month(waktu_transaksi)'), '=', $bulan)
            ->where(DB::raw('year(waktu_transaksi)'), '=', $tahun)
            ->groupBy(['nama_customer', 'month', 'year'])
            ->orderBy('jumlah_transaksi', 'desc')
            ->take(5)
            ->get();

        if (count($topDriver) > 0) {
            return response([
                'message' => 'Generate Report Success',
                'data' => $topDriver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }
}
