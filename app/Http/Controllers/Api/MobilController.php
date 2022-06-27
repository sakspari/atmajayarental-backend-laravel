<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MobilController extends Controller
{
    public function index()
    {
        $mobil = DB::table('mobils')->get();
//        $mobil = Mobil::all();

        if (count($mobil) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function availableInTime(Request $request)
    {
        $timeParams = $request->all();
        $from = Carbon::parse($timeParams['waktu_mulai'])->format('Y-m-d H:i');
        $to = Carbon::parse($timeParams['waktu_selesai'])->format('Y-m-d H:i');

//        $sql = DB::table('transaksi_peminjaman')
//            ->select('id_mobil')
//            ->whereBetween('waktu_mulai', [$from, $to])
//            ->orWhereBetween('waktu_selesai', [$from, $to])
//            ->orderBy('created_at', 'asc')
//            ->where('status_transaksi','<>','5')
//            ->get()
//            ->toArray();

        $sql = DB::table('transaksi_peminjaman')
            ->select('id_mobil')
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('waktu_mulai', [$from, $to]);
                $query->orWhereBetween('waktu_selesai', [$from, $to]);
            })
            ->where('status_transaksi','<>','5')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        $sqlFromCheck = DB::table('transaksi_peminjaman')
            ->select('id_mobil')
            ->where('waktu_mulai', '<=', $from)
            ->where('waktu_selesai', '>=', $from)
            ->where('status_transaksi', '<>', '5')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        $sqlToCheck = DB::table('transaksi_peminjaman')
            ->select('id_mobil')
            ->where('waktu_mulai', '<=', $to)
            ->where('waktu_selesai', '>=', $to)
            ->where('status_transaksi', '<>', '5')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();


//        convert ke array of id
        $arrayOfId = array_map(function ($item) {
            return $item->id_mobil;
        }, $sql);

        $arrayOfIdFrom = array_map(function ($item) {
            return $item->id_mobil;
        }, $sqlFromCheck);

        $arrayOfIdTo = array_map(function ($item) {
            return $item->id_mobil;
        }, $sqlToCheck);


        $mobil = DB::table('mobils')
            ->whereNotIn('id_mobil', $arrayOfId)
            ->whereNotIn('id_mobil', $arrayOfIdFrom)
            ->whereNotIn('id_mobil', $arrayOfIdTo)
            ->get();


        if (count($mobil) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null

        ], 404);
    }

    public function timeoutAsset()
    {
        $mobil = DB::table('mobils')->get();
//        $mobil = Mobil::all();

        if (count($mobil) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobil
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
            'tipe_mobil' => 'required',
            'plat_mobil' => 'required|unique:mobils,plat_mobil',
            'jenis_bahan_bakar' => 'required',
            'jenis_transmisi' => 'required',
            'servis_terakhir' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        //generate Custom id for Mobil
        $lastMobilId = Mobil::select('id_mobil')->orderBy('created_at', 'desc')->first();
        $currentId = substr($lastMobilId, -5, 3);
        $customId = 'ASET' . Carbon::now()->format('ymd') . '-' . sprintf("%03d", intval($currentId) + 1);
        $storeData['id_mobil'] = $customId;

        if ($request->hasFile('foto_mobil')) {
            $file = $request->file('foto_mobil');
            $image = public_path() . '/mobil/' . $customId . '/car_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/mobil/' . $customId . '/car_pict/' . $file->getClientOriginalName();
            $storeData['foto_mobil'] = $image;
        } else
            $storeData['foto_mobil'] = null;

        $mobil = Mobil::create($storeData);

        return response([
            'message' => 'Add Mobil success',
            'data' => $mobil
        ], 200);
    }

    public function show($id)
    {
        $mobil = Mobil::find($id);

        if (!is_null($mobil)) {
            return response([
                'message' => 'Retrive Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $mobil = Mobil::find($id);

        if (is_null($mobil)) {
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 400);
        }//return message saat database tidak ditemukan

        if ($mobil->delete()) {
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Delete Mobil Failed',
            'data' => null
        ], 400);
    }


    public function update(Request $request, $id)
    {
        $mobil = Mobil::find($id);
        if (is_null($mobil)) {
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'tipe_mobil' => 'required',
            'plat_mobil' => 'required|unique:mobils,plat_mobil,' . $mobil->id_mobil . ',id_mobil',
            'jenis_bahan_bakar' => 'required',
            'jenis_transmisi' => 'required',
            'servis_terakhir' => 'required|date|date_format:Y-m-d',
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if ($request->hasFile('foto_mobil')) {
            $file = $request->file('foto_mobil');
            $image = public_path() . '/mobil/' . $mobil->id_mobil . '/car_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/mobil/' . $mobil->id_mobil . '/car_pict/' . $file->getClientOriginalName();
            $updateData['foto_mobil'] = $image;
            $mobil->foto_mobil = $updateData['foto_mobil'];
        }

        if (isset($updateData['id_mitra']))
            $mobil->id_mitra = $updateData['id_mitra'];
        $mobil->plat_mobil = $updateData['plat_mobil'];
        $mobil->no_stnk = $updateData['no_stnk'];
        $mobil->nama_mobil = $updateData['nama_mobil'];
        $mobil->tipe_mobil = $updateData['tipe_mobil'];
        $mobil->jenis_aset = $updateData['jenis_aset'];
        $mobil->jenis_transmisi = $updateData['jenis_transmisi'];
        $mobil->jenis_bahan_bakar = $updateData['jenis_bahan_bakar'];
        $mobil->volume_bahan_bakar = $updateData['volume_bahan_bakar'];
        $mobil->warna_mobil = $updateData['warna_mobil'];
        $mobil->fasilitas_mobil = $updateData['fasilitas_mobil'];
        $mobil->volume_bagasi = $updateData['volume_bagasi'];
        $mobil->kapasitas_penumpang = $updateData['kapasitas_penumpang'];
        $mobil->harga_sewa = $updateData['harga_sewa'];
        $mobil->servis_terakhir = $updateData['servis_terakhir'];
        if (isset($updateData['periode_mulai']))
            $mobil->periode_mulai = $updateData['periode_mulai'];
        if (isset($updateData['periode_selesai']))
            $mobil->periode_selesai = $updateData['periode_selesai'];

        if ($mobil->save()) {
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }
        return response([
            'message' => 'Update Mobil Failed',
            'data' => null
        ], 400);
    }
}
