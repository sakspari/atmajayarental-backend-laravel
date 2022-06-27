<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use function PHPUnit\Framework\isNull;

class DriverController extends Controller
{
    public function index()
    {
        $driver = Driver::all();

        if (count($driver) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404);
    }

    public function getAvailableDriver(Request $request)
    {
        $timeParams = $request->all();
        $from = Carbon::parse($timeParams['waktu_mulai'])->format('Y-m-d H:i');
        $to = Carbon::parse($timeParams['waktu_selesai'])->format('Y-m-d H:i');

        $sql = DB::table('transaksi_peminjaman')
            ->select('id_driver')
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('waktu_mulai', [$from, $to]);
                $query->orWhereBetween('waktu_selesai', [$from, $to]);
            })
            ->where('status_transaksi', '<>', '5')
            ->whereNotNull('id_driver')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        $sqlFromCheck = DB::table('transaksi_peminjaman')
            ->select('id_driver')
            ->where('waktu_mulai', '<=', $from)
            ->where('waktu_selesai', '>=', $from)
            ->whereNotNull('id_driver')
            ->where('status_transaksi', '<>', '5')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        $sqlToCheck = DB::table('transaksi_peminjaman')
            ->select('id_driver')
            ->where('waktu_mulai', '<=', $to)
            ->where('waktu_selesai', '>=', $to)
            ->where('status_transaksi', '<>', '5')
            ->whereNotNull('id_driver')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

//        convert ke array of id
        $arrayOfId = array_map(function ($item) {
            return $item->id_driver;
        }, $sql);

        $arrayOfIdFrom = array_map(function ($item) {
            return $item->id_driver;
        }, $sqlFromCheck);

        $arrayOfIdTo = array_map(function ($item) {
            if ($item != null)
                return $item->id_driver;
        }, $sqlToCheck);


        $drivers = DB::table('drivers')
            ->whereNotIn('id', $arrayOfId)
            ->whereNotIn('id', $arrayOfIdFrom)
            ->whereNotIn('id', $arrayOfIdTo)
            ->get();


        if (count($drivers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
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
            'birthdate' => 'required|date|date_format:Y-m-d',
            'phone' => 'required|regex:/^([0][8][0-9]{8,11})$/u',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        //generate Custom id for Driver
        $lastDriverId = Driver::select('id')->orderBy('created_at', 'desc')->first();
        $currentId = substr($lastDriverId, -5, 3);
        $customId = "DRV" . Carbon::now()->format('ymd') . '-' . sprintf("%03d", intval($currentId) + 1);
        $storeData['id'] = $customId;
        $storeData['password'] = Carbon::parse($storeData['birthdate'])->format('dmY');
        $storeData['status'] = true;

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $image = public_path() . '/driver/' . $customId . '/profile_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/driver/' . $customId . '/profile_pict/' . $file->getClientOriginalName();
            $storeData['picture'] = $image;
        }

        if ($request->hasFile('file_sim')) {
            $file = $request->file('file_sim');
            $file_path = public_path() . '/driver/' . $customId . '/driver_sim/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $customId . '/driver_sim/' . $file->getClientOriginalName();
            $storeData['file_sim'] = $file_path;
        }

        if ($request->hasFile('file_bebas_napza')) {
            $file = $request->file('file_bebas_napza');
            $file_path = public_path() . '/driver/' . $customId . '/file_bebas_napza/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $customId . '/file_bebas_napza/' . $file->getClientOriginalName();
            $storeData['file_bebas_napza'] = $file_path;
        }

        if ($request->hasFile('file_sk_jiwa')) {
            $file = $request->file('file_sk_jiwa');
            $file_path = public_path() . '/driver/' . $customId . '/file_sk_jiwa/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $customId . '/file_sk_jiwa/' . $file->getClientOriginalName();
            $storeData['file_sk_jiwa'] = $file_path;
        }

        if ($request->hasFile('file_sk_jasmani')) {
            $file = $request->file('file_sk_jasmani');
            $file_path = public_path() . '/driver/' . $customId . '/file_sk_jasmani/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $customId . '/file_sk_jasmani/' . $file->getClientOriginalName();
            $storeData['file_sk_jasmani'] = $file_path;
        }

        if ($request->hasFile('file_skck')) {
            $file = $request->file('file_skck');
            $file_path = public_path() . '/driver/' . $customId . '/file_skck/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $customId . '/file_skck/' . $file->getClientOriginalName();
            $storeData['file_skck'] = $file_path;
        }


        $driver = Driver::create($storeData);
        $account = [
            'name' => $storeData['name'],
            'email' => $storeData['email'],
            'password' => $storeData['password'],
            'level' => 'DRIVER',
        ];
        $requestAccount = new Request($account);
        $registerAccount = (new  AuthController)->register($requestAccount);

        return response([
            'message' => 'Add Driver success',
            'data' => $driver,
            'account' => $registerAccount
        ], 200);
    }

    public function show($id)
    {
        $driver = Driver::find($id);

        if (!is_null($driver)) {
            return response([
                'message' => 'Retrive All Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404);
    }

    public function showByEmail($email)
    {
        $driver = Driver::where('email', '=', $email)->get();;

        if (!is_null($driver)) {
            return response([
                'message' => 'Retrive All Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        $user = User::where('email', 'like', $driver->email)->first();

        if (is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 400);
        }//return message saat database tidak ditemukan
        
        if($user!=null){
            $user->delete();
        }

        if ($driver->delete()) {
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null
        ], 400);
    }


    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);

        if (is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 400);
        }
        $user = User::where('email', 'like', $driver->email)->first();

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
            $image = public_path() . '/driver/' . $driver->id . '/profile_pict/';
            $file->move($image, $file->getClientOriginalName());
            $image = '/driver/' . $driver->id . '/profile_pict/' . $file->getClientOriginalName();
            $updateData['picture'] = $image;
            $driver->picture = $updateData['picture'];
        }

        if ($request->hasFile('file_sim')) {
            $file = $request->file('file_sim');
            $file_path = public_path() . '/driver/' . $driver->id . '/driver_sim/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $driver->id . '/driver_sim/' . $file->getClientOriginalName();
            $updateData['file_sim'] = $file_path;
            $driver->file_sim = $updateData['file_sim'];
        }

        if ($request->hasFile('file_bebas_napza')) {
            $file = $request->file('file_bebas_napza');
            $file_path = public_path() . '/driver/' . $driver->id . '/file_bebas_napza/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $driver->id . '/file_bebas_napza/' . $file->getClientOriginalName();
            $updateData['file_bebas_napza'] = $file_path;
            $driver->file_bebas_napza = $updateData['file_bebas_napza'];
        }

        if ($request->hasFile('file_sk_jiwa')) {
            $file = $request->file('file_sk_jiwa');
            $file_path = public_path() . '/driver/' . $driver->id . '/file_sk_jiwa/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $driver->id . '/file_sk_jiwa/' . $file->getClientOriginalName();
            $updateData['file_sk_jiwa'] = $file_path;
            $driver->file_sk_jiwa = $updateData['file_sk_jiwa'];
        }

        if ($request->hasFile('file_sk_jasmani')) {
            $file = $request->file('file_sk_jasmani');
            $file_path = public_path() . '/driver/' . $driver->id . '/file_sk_jasmani/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $driver->id . '/file_sk_jasmani/' . $file->getClientOriginalName();
            $updateData['file_sk_jasmani'] = $file_path;
            $driver->file_sk_jasmani = $updateData['file_sk_jasmani'];
        }

        if ($request->hasFile('file_skck')) {
            $file = $request->file('file_skck');
            $file_path = public_path() . '/driver/' . $driver->id . '/file_skck/';
            $file->move($file_path, $file->getClientOriginalName());
            $file_path = '/driver/' . $driver->id . '/file_skck/' . $file->getClientOriginalName();
            $updateData['file_skck'] = $file_path;
            $driver->file_skck = $updateData['file_skck'];
        }

        $driver->name = $updateData['name'];
        $driver->address = $updateData['address'];
        $driver->birthdate = $updateData['birthdate'];
        $driver->gender = $updateData['gender'];
        $driver->email = $updateData['email'];
        $driver->phone = $updateData['phone'];
        $driver->language = $updateData['language'];
        $driver->price = $updateData['price'];
        if (array_key_exists('status', $updateData))
            $driver->status = $updateData['status'];

        if($user!=null){
            $user->name = $driver->name;
            $user->email = $driver->email;
            $user->level = 'DRIVER';
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

        if ($driver->save()) {
            return response([
                'message' => 'Update Driver Success',
                'data' => [$driver],
                'account' => $user
            ], 200);
        }
        return response([
            'message' => 'Update Driver Failed',
            'data' => null
        ], 400);
    }

}
