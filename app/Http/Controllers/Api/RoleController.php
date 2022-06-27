<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(){
        $role = Role::all();
        if(count($role)>0){
            return response([
                'message' =>'Retrieve All Success',
                'data' =>$role
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
        $validate = Validator::make($storeData, [
            'role_id' => 'required|regex:/^[a-zA-Z ]*$/|unique:roles,role_id',
            'role_name' => 'required|',
        ]); // validasi inputan

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

      $role = Role::create(
            $storeData
        );
        return response([
            'message' => 'Add Role Success',
            'data' => $role
        ], 200); //return data course dalam bentuk JSON
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if(is_null($role)){
            return response([
                'message'=>'Role Not Found',
                'data'=>null
            ],400);
        }//return message saat database tidak ditemukan

        if($role->delete()){
            return response([
                'message'=>'Delete Role Success',
                'data'=>$role
            ],200);
        }

        return response([
            'message'=>'Delete Role Failed',
            'data'=>null
        ],400);
    }

    public function update(Request $request, $id){
        $role = Role::find($id);
        if(is_null($role)){
            return response([
                'message'=>'Role Not Found',
                'data'=>null
            ],400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'role_id' => 'required',
            'role_name' => 'required'
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $role->role_id = $updateData['role_id'];
        $role->role_name = $updateData['role_name'];

        if($role->save()){
            return response([
                'message'=> 'Update Role Success',
                'data'=>$role
            ],200);
        }
        return response([
            'message'=>'Update Role Failed',
            'data'=>null
        ],400);
    }
}
