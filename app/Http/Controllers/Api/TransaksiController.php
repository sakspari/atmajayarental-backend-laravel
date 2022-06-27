<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\TransaksiPeminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    // select all transaction
    public function index()
    {
//        $transaksi = TransaksiPeminjaman::all();
        $transaksi = DB::table('transaksi_peminjaman')
            ->select([
                'id_transaksi',
                'mobils.id_mobil',
                'mobils.nama_mobil',
                'id_customer',
                'id_driver',
                'id_pegawai',
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
                'kode_promo',
                'rating_driver',
                'review_driver',
                'customers.picture as foto_customer',
                'customers.name as nama_customer',
                'customers.sim as sim_customer',
                'customers.idcard as idcard_customer',
                'mobils.foto_mobil',
                'mobils.harga_sewa as harga_satuan_mobil',
                'drivers.price as harga_satuan_driver',
                'drivers.name as nama_driver',
                'drivers.picture as foto_driver',
                'pegawais.name as nama_pegawai',

            ])
            ->join('mobils', 'mobils.id_mobil', '=', 'transaksi_peminjaman.id_mobil')
            ->join('customers', 'transaksi_peminjaman.id_customer', '=', 'customers.id')
            ->leftJoin('drivers', 'transaksi_peminjaman.id_driver', '=', 'drivers.id')
            ->leftJoin('pegawais', 'transaksi_peminjaman.id_pegawai', '=', 'pegawais.id')
            ->orderBy('transaksi_peminjaman.created_at', 'desc')
            ->get();

        if (count($transaksi) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    // select all transaction of customer
    public function transaksiCustomer($id_customer)
    {

        $transaksi = DB::table('transaksi_peminjaman')
            ->select([
                'id_transaksi',
                'mobils.id_mobil',
                'mobils.nama_mobil',
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
                'review_driver',
                'customers.picture as foto_customer',
                'customers.name as nama_customer',
                'customers.sim as sim_customer',
                'customers.idcard as idcard_customer',
                'mobils.foto_mobil',
                'mobils.harga_sewa as harga_satuan_mobil',
                'drivers.price as harga_satuan_driver',
                'drivers.name as nama_driver',
                'drivers.picture as foto_driver',
                'pegawais.name as nama_pegawai',
            ])
            ->join('mobils', 'mobils.id_mobil', '=', 'transaksi_peminjaman.id_mobil')
            ->join('customers', 'transaksi_peminjaman.id_customer', '=', 'customers.id')
            ->leftJoin('drivers', 'transaksi_peminjaman.id_driver', '=', 'drivers.id')
            ->leftJoin('pegawais', 'transaksi_peminjaman.id_pegawai', '=', 'pegawais.id')
            ->where('id_customer', '=', $id_customer)
            ->orderBy('transaksi_peminjaman.created_at', 'desc')
            ->get();

        if (count($transaksi) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    // select all transaction of driver
    public function transaksiDriver($id_driver)
    {
        $transaksi = DB::table('transaksi_peminjaman')
            ->select([
                'id_transaksi',
                'mobils.id_mobil',
                'mobils.nama_mobil',
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
                'review_driver',
                'customers.picture as foto_customer',
                'customers.name as nama_customer',
                'customers.sim as sim_customer',
                'customers.idcard as idcard_customer',
                'mobils.foto_mobil',
                'mobils.harga_sewa as harga_satuan_mobil',
                'drivers.price as harga_satuan_driver',
                'drivers.name as nama_driver',
                'pegawais.name as nama_pegawai',
            ])
            ->join('mobils', 'mobils.id_mobil', '=', 'transaksi_peminjaman.id_mobil')
            ->join('customers', 'transaksi_peminjaman.id_customer', '=', 'customers.id')
            ->leftJoin('drivers', 'transaksi_peminjaman.id_driver', '=', 'drivers.id')
            ->leftJoin('pegawais', 'transaksi_peminjaman.id_pegawai', '=', 'pegawais.id')
            ->where('id_driver', '=', $id_driver)
            ->orderBy('transaksi_peminjaman.created_at', 'desc')
            ->get();

        if (count($transaksi) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    //rating performa driver
    public function rerataPerformaDriver($id_driver)
    {
        $numberOfTransaksiDriver = DB::table('transaksi_peminjaman')
            ->select('id_transaksi')
            ->where('id_driver', '=', $id_driver)
            ->count('id_transaksi');

        $numberOfRatingGiven = DB::table('transaksi_peminjaman')
            ->select('id_transaksi')
            ->where('id_driver', '=', $id_driver)
            ->where('rating_driver', '<>', null)
            ->count('id_transaksi');

        $avgRating = DB::table('transaksi_peminjaman')
            ->select('rating_driver')
            ->where('id_driver', '=', $id_driver)
            ->whereNotNull('rating_driver')
            ->average('rating_driver');

        if (!is_null($numberOfTransaksiDriver) && $numberOfTransaksiDriver != 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => [
                    'jumlah_transaksi' => $numberOfTransaksiDriver,
                    'jumlah_rating' => $numberOfRatingGiven,
                    'rerata_rating' => $avgRating,
                ]
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_mobil' => 'required',
            'id_customer' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

//        cek transaksi yang sedang berjalan / belum selesai
        $isRunning = DB::table('transaksi_peminjaman')
            ->where('id_customer', '=', $storeData['id_customer'])
            ->where('status_transaksi', '<>', "5") // 5 selesai
            ->get()
            ->count();

        if ($isRunning > 0) {
            return response(['message' => 'sedang ada transaksi berjalan!'], 400);
        }

        //generate Custom id for Transaksi
        if (isset($storeData['id_driver']))
            $prefixType = "01"; //peminjaman dengan driver
        else
            $prefixType = "00";

        $lastTransaksiId = TransaksiPeminjaman::select('id_transaksi')->orderBy('created_at', 'desc')->first();
        $currentId = substr($lastTransaksiId, -5, 3);
        $customId = "TRN" . Carbon::now()->format('ymd') . $prefixType . '-' . sprintf("%03d", intval($currentId) + 1);
        $storeData['id_transaksi'] = $customId;
        $storeData['waktu_transaksi'] = Carbon::now()->format('Y-m-d H:i:s');


        $transaksi = TransaksiPeminjaman::create($storeData);

        return response([
            'message' => 'Add Transaksi success',
            'data' => $transaksi
        ], 200);
    }

    public function show($id)
    {
        $transaksi = TransaksiPeminjaman::find($id);

        if (!is_null($transaksi)) {
            return response([
                'message' => 'Retrive All Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPeminjaman::find($id);

        if (is_null($transaksi)) {
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 400);
        }//return message saat database tidak ditemukan

        if ($transaksi->delete()) {
            return response([
                'message' => 'Delete Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Delete Transaksi Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPeminjaman::find($id);
        if (is_null($transaksi)) {
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_transaksi' => 'required',
            'id_mobil' => 'required',
            'id_customer' => 'required',
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $image = public_path() . '/transaksi/' . $transaksi->id_transaksi . '/bukti_pembayaran/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/transaksi/' . $transaksi->id_transaksi . '/bukti_pembayaran/' . $file->getClientOriginalName();
            $updateData['bukti_pembayaran'] = $image;
        }

        if (array_key_exists('id_driver', $updateData))
            $transaksi->id_driver = $updateData['id_driver'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        if (array_key_exists('waktu_pengembalian', $updateData)) {
            $transaksi->waktu_pengembalian = Carbon::parse($updateData['waktu_pengembalian'])->format('Y-m-d H:i:s');
        }
        if (array_key_exists('subtotal_mobil', $updateData))
            $transaksi->subtotal_mobil = $updateData['subtotal_mobil'];
        if (array_key_exists('subtotal_driver', $updateData))
            $transaksi->subtotal_driver = $updateData['subtotal_driver'];
        if (array_key_exists('total_denda', $updateData))
            $transaksi->total_denda = $updateData['total_denda'];
        if (array_key_exists('total_diskon', $updateData))
            $transaksi->total_diskon = $updateData['total_diskon'];
        if (array_key_exists('grand_total', $updateData))
            $transaksi->grand_total = $updateData['grand_total'];
        if (array_key_exists('metode_pembayaran', $updateData))
            $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        if (array_key_exists('kode_promo', $updateData))
            $transaksi->kode_promo = $updateData['kode_promo'];
        $transaksi->status_transaksi = $updateData['status_transaksi'];
        if (array_key_exists('rating_driver', $updateData))
            $transaksi->rating_driver = $updateData['rating_driver'];
        if (array_key_exists('review_driver', $updateData))
            $transaksi->review_driver = $updateData['review_driver'];
        if (array_key_exists('bukti_pembayaran', $updateData))
            $transaksi->bukti_pembayaran = $updateData['bukti_pembayaran'];

        if ($transaksi->save()) {
            return response([
                'message' => 'Update Transaksi Success',
                'data' => [$transaksi]
            ], 200);
        }
        return response([
            'message' => 'Update Transaksi Failed',
            'data' => null
        ], 400);
    }

}
