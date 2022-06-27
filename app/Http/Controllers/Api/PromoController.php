<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    //
    public function index(){
        $promos = Promo::all();

        if(count($promos)>0){
            return response([
                'message' =>'Retrieve All Success',
                'data' =>$promos
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' =>null
        ],404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'kode_promo' => 'required|unique:promos,kode_promo',
            'persen_diskon' => 'required|numeric'
        ]);

        if($validate->fails()){
            return response(['message'=>$validate->errors()], 400);
        }

        if(is_null($storeData['status_promo']))
            $storeData['status_promo'] = 1;

        $promo = Promo::create($storeData);

        return response([
            'message' => 'Add promo success',
            'data' => $promo
        ],200);
    }

    public function show($id)
    {
        $promo = Promo::find($id);

        if (!is_null($promo)) {
            return response([
                'message' => 'Retrive All Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $promo = Promo::find($id);

        if(is_null($promo)){
            return response([
                'message'=>'Promo Not Found',
                'data'=>null
            ],400);
        }//return message saat database tidak ditemukan

        if($promo->delete()){
            return response([
                'message'=>'Delete Promo Success',
                'data'=>$promo
            ],200);
        }

        return response([
            'message'=>'Delete Promo Failed',
            'data'=>null
        ],400);
    }

    public function update(Request $request, $id){
        $promo = Promo::find($id);
        if(is_null($promo)){
            return response([
                'message'=>'Promo Not Found',
                'data'=>null
            ],400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'persen_diskon' => 'required|numeric',
            'status_promo' => 'required'
             ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $promo->jenis_promo = $updateData['jenis_promo'];
        $promo->deskripsi_promo = $updateData['deskripsi_promo'];
        $promo->persen_diskon = $updateData['persen_diskon'];
        $promo->status_promo = $updateData['status_promo'];

        if($promo->save()){
            return response([
                'message'=> 'Update Promo Success',
                'data'=>$promo
            ],200);
        }
        return response([
            'message'=>'Update Promo Failed',
            'data'=>null
        ],400);
    }

}
