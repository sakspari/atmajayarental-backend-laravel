<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function index(){
        $jadwal = Jadwal::all();
        if(count($jadwal)>0){
            return response([
                'message' =>'Retrieve All Success',
                'data' =>$jadwal
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' =>null
        ],404);
    }

    public function show($id_jadwal){
        $jadwal = Jadwal::find($id_jadwal);

        if(!is_null($jadwal)){
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal
            ],200);
        }

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'hari' => 'required',
            'sesi' => 'required|numeric',
        ]); // validasi inputan

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $jadwal = Jadwal::create(
            $storeData
        );
        return response([
            'message' => 'Add Jadwal Success',
            'data' => $jadwal
        ], 200); //return data course dalam bentuk JSON
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        if(is_null($jadwal)){
            return response([
                'message'=>'Jadwal Not Found',
                'data'=>null
            ],400);
        }//return message saat database tidak ditemukan

        if($jadwal->delete()){
            return response([
                'message'=>'Delete Jadwal Success',
                'data'=>$jadwal
            ],200);
        }

        return response([
            'message'=>'Delete Jadwal Failed',
            'data'=>null
        ],400);
    }

    public function update(Request $request, $id){
        $jadwal = Jadwal::find($id);
        if(is_null($jadwal)){
            return response([
                'message'=>'Jadwal Not Found',
                'data'=>null
            ],400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'hari' => 'required',
            'sesi' => 'required|numeric'
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $jadwal->hari = $updateData['hari'];
        $jadwal->sesi = $updateData['sesi'];

        if($jadwal->save()){
            return response([
                'message'=> 'Update Jadwal Success',
                'data'=>$jadwal
            ],200);
        }
        return response([
            'message'=>'Update Jadwal Failed',
            'data'=>null
        ],400);
    }
}
