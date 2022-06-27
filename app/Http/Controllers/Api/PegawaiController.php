<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;
use Faker\Core\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index()
    {

        $pegawaiWRoles = DB::table('pegawais')
            ->join('roles', 'roles.role_id', '=', 'pegawais.role_id')
            ->orderBy('pegawais.created_at', 'asc')
            ->get();

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
            'name' => 'required|regex:/^[a-zA-Z ]*$/',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'role_id' => 'required',
            'birthdate' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        //generate Custom id for Pegawai
        $lastPegawaiId = Pegawai::select('id')->orderBy('created_at', 'desc')->first();
        $currentId = substr($lastPegawaiId, -5, 3);
        $customId = $storeData['role_id'] . Carbon::now()->format('ymd') . '-' . sprintf("%03d", intval($currentId) + 1);
        $storeData['id'] = $customId;
        $storeData['password'] = Carbon::parse($storeData['birthdate'])->format('dmY');

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $image = public_path() . '/pegawai/' . $customId . '/profile_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/pegawai/' . $customId . '/profile_pict/' . $file->getClientOriginalName();
            $storeData['picture'] = $image;
        } else
            $storeData['picture'] = null;

        if ($storeData['role_id'] == 'ADM')
            $level = "ADMIN";
        else if ($storeData['role_id'] == 'CS')
            $level = "CS";
        else
            $level = "MANAGER";

        $account = [
            'name' => $storeData['name'],
            'email' => $storeData['email'],
            'password' => $storeData['password'],
            'level' => $level,
        ];
        $requestAccount = new Request($account);
        $registerAccount = (new  AuthController)->register($requestAccount);

        $pegawai = Pegawai::create($storeData);

        return response([
            'message' => 'Add Pegawai success',
            'data' => $pegawai,
            'account' => $registerAccount
        ], 200);
    }

    public function show($id)
    {
        $pegawai = Pegawai::find($id);

        if (!is_null($pegawai)) {
            return response([
                'message' => 'Retrive Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404);
    }

    public function showByEmail($email)
    {
        $pegawai = Pegawai::join('roles', 'roles.role_id', '=', 'pegawais.role_id')
            ->where('email', '=', $email)
            ->get();

        if (!is_null($pegawai)) {
            return response([
                'message' => 'Retrive Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);
        $user = User::where('email', 'like', $pegawai->email)->first();

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 400);
        }//return message saat database tidak ditemukan

        if($user!=null){
            $user->delete();
        }

        if ($pegawai->delete()) {
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);
        $user = User::where('email', 'like', $pegawai->email)->first();
        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required',
            'email' => $user!=null?'required|email:rfc,dns|unique:users,email,' . $user->id : 'required|email:rfc,dns|unique:users,email,',
            'birthdate' => 'required|date|date_format:Y-m-d',
            'phone' => 'required|regex:/^([0][8][0-9]{8,11})$/u', //nomor telp minimal 8 maks 13
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $image = public_path() . '/pegawai/' . $pegawai->id . '/profile_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/pegawai/' . $pegawai->id . '/profile_pict/' . $file->getClientOriginalName();
            $updateData['picture'] = $image;

            $pegawai->picture = $updateData['picture'];
        }

        $pegawai->name = $updateData['name'];
        $pegawai->birthdate = $updateData['birthdate'];
        $pegawai->gender = $updateData['gender'];
        $pegawai->address = $updateData['address'];
        $pegawai->phone = $updateData['phone'];
        $pegawai->email = $updateData['email'];

        if ($pegawai->role_id == 'ADM')
            $level = "ADMIN";
        else if ($pegawai->role_id == 'CS')
            $level = "CS";
        else
            $level = "MANAGER";

        if($user!=null){
            $user->name = $pegawai->name;
            $user->email = $pegawai->email;
            $user->level = $level;
        }

        if (array_key_exists('password', $updateData)) {

            if (array_key_exists('old_password', $updateData)) {
                if (!Hash::check($updateData['old_password'], $user->password))
                    return response(['message' => 'Update Failed: Invalid credentials'], 401);
                else
                    $user->password = bcrypt($updateData['password']);
            } else
                return response(['message' => 'current password needed!'], 401);
        }
        
        if($user!=null){
            $user->save();
        }

        if ($pegawai->save()) {
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai,
                'account' => $user
            ], 200);
        }
        return response([
            'message' => 'Update Pegawai Failed',
            'data' => null
        ], 400);
    }

}
