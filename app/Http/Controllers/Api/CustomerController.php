<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
//        $customers =  Customer::all();
        $customers = DB::table('customers')
            ->select([
                'customers.*',
                'users.id as user_id',
                DB::raw('(CASE WHEN (users.email_verified_at IS NOT NULL) THEN 1 ELSE 0 END) as verified')
            ])
            ->leftJoin('users', 'customers.email', '=', 'users.email')
            ->orderBy('customers.created_at','desc')
            ->get();
        if (count($customers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);

    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (!is_null($customer)) {
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404);
    }

    public function showByEmail($email)
    {
        $customer = Customer::where('email', '=', $email)->get();

        if (!is_null($customer)) {
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'name' => 'required|regex:/^[a-zA-Z ]*$/',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'birthdate' => 'required|date|date_format:Y-m-d',
            'phone' => 'required|regex:/^([0][8][0-9]{8,11})$/u', //nomor telp minimal 8 maks 13
        ]); // validasi inputan

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        //generate Custom id for customer
        $lastCustomerId = Customer::select('id')->orderBy('id', 'desc')->first();
        $currentId = substr($lastCustomerId, -5, 3);
        $customId = 'CUS' . Carbon::now()->format('ymd') . '-' . sprintf("%03d", intval($currentId) + 1);
        $storeData['id'] = $customId;
        $storeData['password'] = Carbon::parse($storeData['birthdate'])->format('dmY');
        //password di encrypt di AuthController

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $image = public_path() . '/customer/' . $customId . '/profile_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/customer/' . $customId . '/profile_pict/' . $file->getClientOriginalName();
            $storeData['picture'] = $image;
        }

        if ($request->hasFile('sim')) {
            $file = $request->file('sim');
            $file_path = public_path() . '/customer/' . $customId . '/customer_sim/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/customer/' . $customId . '/customer_sim/' . $file->getClientOriginalName();
            $storeData['sim'] = $file_path;
        }

        if ($request->hasFile('idcard')) {
            $file = $request->file('idcard');
            $file_path = public_path() . '/customer/' . $customId . '/customer_idcard/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/customer/' . $customId . '/customer_idcard/' . $file->getClientOriginalName();
            $storeData['idcard'] = $file_path;
        }

        $customer = Customer::create(
            $storeData
        );

        $account = [
            'name' => $storeData['name'],
            'email' => $storeData['email'],
            'password' => $storeData['password'],
            'level' => 'CUSTOMER',
        ];
        $requestAccount = new Request($account);
        $registerAccount = (new AuthController)->register($requestAccount);
        return response([
            'message' => 'Customer Registration Success',
            'data' => $customer,
            'account' => $registerAccount
        ], 200); //return data customer dan account dalam bentuk JSON
    }

//    verifikasi akun customer
    public function verifikasiPendaftaran($customerId)
    {
        $customer = Customer::find($customerId);
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 400);
        }

        $user = User::where('email', 'like', $customer->email)->first();

        if (is_null($user)) {
            return response([
                'message' => 'User Account Not Found',
                'data' => null
            ], 400);
        }

        $user->email_verified_at = Carbon::now()->format('Y-m-d');

        if ($user->save()) {
            return response([
                'message' => 'Customer with id ' . $customer->id . ' verified successfully!',
                'data' => $customer
            ], 200); //return data customer dalam bentuk JSON
        }

        return response([
            'message' => 'Verification failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $customerId)
    {
        $customer = Customer::find($customerId);
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 400);
        }

        $user = User::where('email', 'like', $customer->email)->first();

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required|regex:/^[a-zA-Z ]*$/',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
            'birthdate' => 'required|date|date_format:Y-m-d',
            'phone' => 'required|regex:/^([0][8][0-9]{8,11})$/u', //nomor telp minimal 8 maks 13
        ]); // validasi inputan


        if ($validate->fails()) {
            return response([
                'message' => $validate->errors(),
                'data' => $updateData,
            ], 400);
        }

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $image = public_path() . '/customer/' . $customer->id . '/profile_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/customer/' . $customer->id . '/profile_pict/' . $file->getClientOriginalName();
            $updateData['picture'] = $image;
            $customer->picture = $updateData['picture'];
        }

        if ($request->hasFile('sim')) {
            $file = $request->file('sim');
            $file_path = public_path() . '/customer/' . $customer->id . '/customer_sim/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/customer/' . $customer->id . '/customer_sim/' . $file->getClientOriginalName();
            $updateData['sim'] = $file_path;
            $customer->sim = $updateData['sim'];
        }

        if ($request->hasFile('idcard')) {
            $file = $request->file('idcard');
            $file_path = public_path() . '/customer/' . $customer->id . '/customer_idcard/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/customer/' . $customer->id . '/customer_idcard/' . $file->getClientOriginalName();
            $updateData['idcard'] = $file_path;
            $customer->idcard = $updateData['idcard'];
        }

        $customer->name = $updateData['name'];
        $customer->address = $updateData['address'];
        $customer->birthdate = $updateData['birthdate'];
        $customer->gender = $updateData['gender'];
        $customer->email = $updateData['email'];
        $customer->phone = $updateData['phone'];


        $user->name = $customer->name;
        $user->email = $customer->email;
        $user->level = 'CUSTOMER';
        if (array_key_exists('password', $updateData)) {

            if (array_key_exists('old_password', $updateData)) {
                if (!Hash::check($updateData['old_password'], $user->password))
                    return response(['message' => 'Update Failed: Invalid credentials'], 401);
                else
                    $user->password = bcrypt($updateData['password']);
            } else
                return response(['message' => 'current password needed!'], 401);
        }

        if ($customer->save() && $user->save()) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer,
                'account' => $user
            ], 200); //return data customer dalam bentuk JSON
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null
        ], 400); //return data customer dalam bentuk JSON

    }


}
