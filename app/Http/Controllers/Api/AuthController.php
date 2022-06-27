<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    // read method
    public function index()
    {
        $users = User::all(); //mengambil semua data user

        if (count($users) > 0) {
            return response([
                'message' => 'Retrive All Success',
                'data' => $users
            ], 200);
        }//return semua data dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //return message data kosong
    }

    public function show($id)
    {
        $users = User::find($id); //mencari user berdasarkan id

        if (!is_null($users)) {
            return response([
                'message' => 'Retrive All Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404);
    }

    // add user baru
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]); //rule untuk validasi inputan

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $registrationData['password'] = bcrypt($request->password); //enkripsi pasword
        $user = User::create($registrationData); //crete user baru
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); //return user dalam bentuk json
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); //membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
        if (!Auth::attempt($loginData))
            return response(['message' => 'Invalid credentials'], 401);

        $user = Auth::user();
        if ($user->level == "CUSTOMER" && $user->email_verified_at == null)
            return response(['message' => 'Customer belum verifikasi'], 401);

        if ($user->level == "CUSTOMER")
            $data = Customer::where('email', 'like', $user->email)->first();
        else if ($user->level == "DRIVER")
            $data = Driver::where('email', 'like', $user->email)->first();
        else
            $data = Pegawai::where('email', 'like', $user->email)->first();

        $token = $user->createToken('Authentication Token')->accessToken; // generate token
        return response([
            'message' => 'Auntenthicated',
            'user' => $user,
            'user_detail' => $data,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); //return data user dan token dalam bentuk json
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if ($logout) {
            return response()->json([
                'message' => 'Logout success'
            ]);
        }
    }

    //    method untuk menghapus data tertentu
    public function destroy($email)
    {
        $users = User::where('email', '=', $email)->first();

        if (is_null($users)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 400);
        }//return message saat database tidak ditemukan

        if ($users->delete()) {
            return response([
                'message' => 'Delete User Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'Delete user Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $email)
    {
        $users = User::where('email', '=', $email)->first();
        if (is_null($users)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => ['max:50', 'required', 'alpha'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($users)],
            'password' => 'required',
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $users->name = $updateData['name']; //edit nama user
        $users->email = $updateData['email'];
        if (!is_null($updateData['password']))
            $users->password = bcrypt($updateData['password']);; //edit password
        if (array_key_exists('level', $updateData))
            $users->level = $updateData['level'];


        if ($users->save()) {
            return response([
                'message' => 'Update User Success',
                'data' => $users
            ], 200);
        }
        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
    }
}
