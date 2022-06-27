<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MitraController extends Controller
{
    public function index(){
        $mitra = Mitra::all();
        if(count($mitra)>0){
            return response([
                'message' =>'Retrieve All Success',
                'data' =>$mitra
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' =>null
        ],404);
    }

    public function show($id){
        $mitra = Mitra::find($id);

        if(!is_null($mitra)){
            return response([
                'message' => 'Retrieve Mitra Success',
                'data' => $mitra
            ],200);
        }

        return response([
            'message' => 'Mitra Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nik_mitra' => 'required|numeric|unique:mitras,nik_mitra',
            'nama_mitra' => 'required',
            'alamat_mitra' => 'required',
            'no_telp_mitra' => 'required',
        ]); // validasi inputan

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $mitra = Mitra::create(
            $storeData
        );
        return response([
            'message' => 'Add Mitra Success',
            'data' => $mitra
        ], 200); //return data course dalam bentuk JSON
    }

    public function destroy($id)
    {
        $mitra = Mitra::find($id);

        if(is_null($mitra)){
            return response([
                'message'=>'Mitra Not Found',
                'data'=>null
            ],400);
        }//return message saat database tidak ditemukan

        if($mitra->delete()){
            return response([
                'message'=>'Delete Mitra Success',
                'data'=>$mitra
            ],200);
        }

        return response([
            'message'=>'Delete Mitra Failed',
            'data'=>null
        ],400);
    }

    public function update(Request $request, $id){
        $mitra = Mitra::find($id);
        if(is_null($mitra)){
            return response([
                'message'=>'Mitra Not Found',
                'data'=>null
            ],400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nik_mitra' => 'required|numeric|unique:mitras,nik_mitra,'.$mitra->id,
            'nama_mitra' => 'required',
            'alamat_mitra' => 'required',
            'no_telp_mitra' => 'required',
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $mitra->nik_mitra = $updateData['nik_mitra'];
        $mitra->nama_mitra = $updateData['nama_mitra'];
        $mitra->alamat_mitra = $updateData['alamat_mitra'];
        $mitra->no_telp_mitra = $updateData['no_telp_mitra'];

        if($mitra->save()){
            return response([
                'message'=> 'Update Mitra Success',
                'data'=>$mitra
            ],200);
        }
        return response([
            'message'=>'Update Mitra Failed',
            'data'=>null
        ],400);
    }
}
