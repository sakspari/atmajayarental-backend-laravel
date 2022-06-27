<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DetailJadwalController extends Controller
{
    public function index()
    {

        $detailJadwal = DB::table('detail_jadwals')
            ->select([
                'detail_jadwals.id',
                'jadwals.hari',
                'jadwals.sesi',
                'detail_jadwals.jam_mulai',
                'detail_jadwals.jam_selesai',
                'pegawais.id as id_pegawai',
                'pegawais.name as nama_pegawai',
                'pegawais.role_id',
            ])
            ->join('jadwals', 'jadwals.id', '=', 'detail_jadwals.id_jadwal')
            ->join('pegawais', 'detail_jadwals.id_pegawai', '=', 'pegawais.id')
            ->orderBy('detail_jadwals.created_at', 'asc')
            ->get();

        if (count($detailJadwal) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function show($id)
    {
        $detailJadwal = DetailJadwal::find($id);

        if (!is_null($detailJadwal)) {
            return response([
                'message' => 'Retrieve Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Detail Jadwal Not Found',
            'data' => null
        ], 404);
    }

    public function countPegawai(string $id)
    {
//        TODO: tambahin parameter id Pegawai untuk cek jumlah conut pegawainya kurang dari 6

        $pegawaiWRoles = DB::table('detail_jadwals')
            ->where('id_pegawai', '=', $id)
            ->get()
            ->count();

//        $pegawai = Pegawai::all();

        if (count($pegawaiWRoles) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawaiWRoles
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
            'id_jadwal' => 'required',
            'id_pegawai' => 'required',
        ]); // validasi inputan

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        //check duplicate jadwal
        $isDuplicate = DB::table('detail_jadwals')
            ->where('id_jadwal', '=', $storeData['id_jadwal'])
            ->where('id_pegawai', '=', $storeData['id_pegawai'])
            ->get()
            ->count();

        if ($isDuplicate != 0) {
            return response(['message' => 'data jadwal pegawai ' . $storeData['id_pegawai'] . ' pada sesi ini telah ada!'], 400);
        }

        //cek jumlah shift
        $numberOfShift = DB::table('detail_jadwals')
            ->where('id_pegawai', '=', $storeData['id_pegawai'])
            ->get()
            ->count();

        if ($numberOfShift >= 6) {
            return response(['message' => 'jumlah shift maksimal untuk pegawai ' . $storeData['id_pegawai'] . ' tercapai!'], 400);
        }

        $detailJadwal = DetailJadwal::create(
            $storeData
        );
        return response([
            'message' => 'Add Data Jadwal Success',
            'data' => $detailJadwal
        ], 200);
    }

    public function destroy($id)
    {
        $detailJadwal = DetailJadwal::find($id);

        if (is_null($detailJadwal)) {
            return response([
                'message' => 'Data Detail Jadwal Not Found',
                'data' => null
            ], 400);
        }//return message saat database tidak ditemukan

        if ($detailJadwal->delete()) {
            return response([
                'message' => 'Delete Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Delete Detail Jadwal Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $detailJadwal = DetailJadwal::find($id);
        if (is_null($detailJadwal)) {
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_jadwal' => 'required',
            'id_pegawai' => 'required'
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $detailJadwal->id_jadwal = $updateData['id_jadwal'];
        $detailJadwal->id_pegawai = $updateData['id_pegawai'];
        $detailJadwal->jam_mulai = $updateData['jam_mulai'];
        $detailJadwal->jam_selesai = $updateData['jam_selesai'];
        
         //check duplicate jadwal
        $isDuplicate = DB::table('detail_jadwals')
            ->where('id_jadwal', '=', $updateData['id_jadwal'])
            ->where('id_pegawai', '=', $updateData['id_pegawai'])
            ->get()
            ->count();

        if ($isDuplicate != 0) {
            return response(['message' => 'data jadwal pegawai ' . $updateData['id_pegawai'] . ' pada sesi ini telah ada!'], 400);
        }

        //cek jumlah shift
        $numberOfShift = DB::table('detail_jadwals')
            ->where('id_pegawai', '=', $updateData['id_pegawai'])
            ->get()
            ->count();

        if ($numberOfShift >= 6) {
            return response(['message' => 'jumlah shift maksimal untuk pegawai ' . $updateData['id_pegawai'] . ' tercapai!'], 400);
        }

        if ($detailJadwal->save()) {
            return response([
                'message' => 'Update Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }
        return response([
            'message' => 'Update Detail Jadwal Failed',
            'data' => null
        ], 400);
    }
}
