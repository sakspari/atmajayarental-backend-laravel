<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login/', 'App\Http\Controllers\Api\AuthController@login'); //Login
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout/', 'App\Http\Controllers\Api\AuthController@logout'); //Logout
});

Route::post('customer/', 'App\Http\Controllers\Api\CustomerController@store'); //register

Route::group(['middleware' => ['auth:api', 'levelcheck:CUSTOMER']], function () {

    Route::get('promo/', 'App\Http\Controllers\Api\PromoController@index');

    Route::get('customer/{customer_id}', 'App\Http\Controllers\Api\CustomerController@show');
    Route::get('customer-email/{customer_email}', 'App\Http\Controllers\Api\CustomerController@showByEmail');
    Route::put('customer/{customer_id}', 'App\Http\Controllers\Api\CustomerController@update');

    Route::post('transaksi/', 'App\Http\Controllers\Api\TransaksiController@store'); // create transaksi
    Route::post('mobil-available/', 'App\Http\Controllers\Api\MobilController@availableInTime'); // cek ketersediaan mobil di rentang waktu tertentu
    Route::post('driver-available/', 'App\Http\Controllers\Api\DriverController@getAvailableDriver'); // cek ketersediaan driver di rentang waktu tertentu

    Route::get('transaksi-customer/{id_customer}', 'App\Http\Controllers\Api\TransaksiController@transaksiCustomer');//update: bukti bayar + rating driver



});

Route::group(['middleware' => ['auth:api', 'levelcheck:DRIVER']], function () {
    Route::get('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@show');
    Route::put('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@update'); // profil + status ketersediaan

});

Route::group(['middleware' => ['auth:api', 'levelcheck:ADMIN']], function () {
    Route::post('pegawai/', 'App\Http\Controllers\Api\PegawaiController@store');
//    Route::get('pegawai', 'App\Http\Controllers\Api\PegawaiController@index');
    Route::get('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@show');
//    Route::put('pegawai/{id_pegawai}','App\Http\Controllers\Api\PegawaiController@update');
    Route::delete('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@destroy');

    Route::post('driver/', 'App\Http\Controllers\Api\DriverController@store');
    Route::get('driver/', 'App\Http\Controllers\Api\DriverController@index');
    Route::get('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@show');
//    Route::put('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@update');
    Route::delete('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@destroy');

    Route::post('mitra/', 'App\Http\Controllers\Api\MitraController@store');
    Route::get('mitra/', 'App\Http\Controllers\Api\MitraController@index');
    Route::get('mitra/{id_mitra}', 'App\Http\Controllers\Api\MitraController@show');
    Route::put('mitra/{id_mitra}', 'App\Http\Controllers\Api\MitraController@update');
    Route::delete('mitra/{id_mitra}', 'App\Http\Controllers\Api\MitraController@destroy');

    Route::post('mobil/', 'App\Http\Controllers\Api\MobilController@store');
    Route::get('mobil/{id_mobil}', 'App\Http\Controllers\Api\MobilController@show');
    Route::put('mobil/{id_mobil}', 'App\Http\Controllers\Api\MobilController@update');
    Route::delete('mobil/{id_mobil}', 'App\Http\Controllers\Api\MobilController@destroy');

});

Route::group(['middleware' => ['auth:api', 'levelcheck:MANAGER']], function () {
    Route::get('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@show');
    Route::get('pegawai-email/{email}', 'App\Http\Controllers\Api\PegawaiController@showByEmail');
//    Route::put('pegawai/{id_pegawai}','App\Http\Controllers\Api\PegawaiController@update');


    Route::post('jadwal/', 'App\Http\Controllers\Api\JadwalController@store');
    Route::get('jadwal/', 'App\Http\Controllers\Api\JadwalController@index');
    Route::get('jadwal/{id_jadwal}', 'App\Http\Controllers\Api\JadwalController@show');
    Route::put('jadwal/{id_jadwal}', 'App\Http\Controllers\Api\JadwalController@update');
    Route::delete('jadwal/{id_jadwal}', 'App\Http\Controllers\Api\JadwalController@destroy');

    Route::post('jadwal-pegawai/', 'App\Http\Controllers\Api\DetailJadwalController@store');
    Route::get('jadwal-pegawai/', 'App\Http\Controllers\Api\DetailJadwalController@index');
    Route::get('jadwal-pegawai/{id_jadwal}', 'App\Http\Controllers\Api\DetailJadwalController@show');
    Route::put('jadwal-pegawai/{id_jadwal}', 'App\Http\Controllers\Api\DetailJadwalController@update');
    Route::delete('jadwal-pegawai/{id_jadwal}', 'App\Http\Controllers\Api\DetailJadwalController@destroy');

    Route::post('promo/', 'App\Http\Controllers\Api\PromoController@store');
    Route::get('promo/{kode_promo}', 'App\Http\Controllers\Api\PromoController@show');
//    Route::get('promo', 'App\Http\Controllers\Api\PromoController@index');
    Route::put('promo/{kode_promo}', 'App\Http\Controllers\Api\PromoController@update');
    Route::delete('promo/{kode_promo}', 'App\Http\Controllers\Api\PromoController@destroy');

    Route::get('penyewaan-mobil/{tahun}/{bulan}', 'App\Http\Controllers\Api\LaporanController@penyewaanMobil');
    Route::get('detail-pendapatan/{tahun}/{bulan}', 'App\Http\Controllers\Api\LaporanController@detailPendapatan');
    Route::get('top-five-driver/{tahun}/{bulan}', 'App\Http\Controllers\Api\LaporanController@driverTransaksiTerbanyak');
    Route::get('top-five-customer/{tahun}/{bulan}', 'App\Http\Controllers\Api\LaporanController@customerTransaksiTerbanyak');
    Route::get('performa-driver/{tahun}/{bulan}', 'App\Http\Controllers\Api\LaporanController@driverPerforma');

});

Route::group(['middleware' => ['auth:api', 'levelcheck:CS']], function () {
//    Route::get('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@show');
//    Route::put('pegawai/{id_pegawai}','App\Http\Controllers\Api\PegawaiController@update');

    Route::get('transaksi/', 'App\Http\Controllers\Api\TransaksiController@index');
    Route::get('transaksi/{id_transaksi}', 'App\Http\Controllers\Api\TransaksiController@show');
    Route::put('transaksi/{id_transaksi}', 'App\Http\Controllers\Api\TransaksiController@update');
    Route::delete('transaksi/{id_transaksi}', 'App\Http\Controllers\Api\TransaksiController@destroy');
});

Route::post('pegawai/', 'App\Http\Controllers\Api\PegawaiController@store');
Route::post('role/', 'App\Http\Controllers\Api\RoleController@store');
Route::get('role/', 'App\Http\Controllers\Api\RoleController@index');
Route::get('role/{id_role}', 'App\Http\Controllers\Api\RoleController@show');
Route::put('role/{id_role}', 'App\Http\Controllers\Api\RoleController@update');
Route::delete('role/{id_role}', 'App\Http\Controllers\Api\RoleController@destroy');

Route::group(['middleware' => ['auth:api', 'levelcheck:ADMIN,MANAGER,CS']], function () {
    Route::get('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@show');
    Route::put('pegawai/{id_pegawai}', 'App\Http\Controllers\Api\PegawaiController@update');
});

Route::group(['middleware' => ['auth:api', 'levelcheck:ADMIN,MANAGER']], function () {
    Route::get('pegawai/', 'App\Http\Controllers\Api\PegawaiController@index');
});

Route::group(['middleware' => ['auth:api', 'levelcheck:CUSTOMER,MANAGER']], function () {
    Route::get('promo/', 'App\Http\Controllers\Api\PromoController@index');
});

Route::group(['middleware' => ['auth:api', 'levelcheck:CUSTOMER,ADMIN']], function () {
    Route::get('mobil/', 'App\Http\Controllers\Api\MobilController@index');
});

Route::group(['middleware' => ['auth:api', 'levelcheck:CUSTOMER,CS']], function () {
    Route::put('transaksi/{id_transaksi}', 'App\Http\Controllers\Api\TransaksiController@update');
});

Route::group(['middleware' => ['auth:api', 'levelcheck:DRIVER']], function () {
    Route::get('transaksi-driver/{id_driver}', 'App\Http\Controllers\Api\TransaksiController@transaksiDriver');
    Route::get('driver-email/{email}', 'App\Http\Controllers\Api\DriverController@showByEmail');
    Route::get('rating-driver/{id_driver}', 'App\Http\Controllers\Api\TransaksiController@rerataPerformaDriver');
});

Route::group(['middleware' => ['auth:api', 'levelcheck:DRIVER,ADMIN']], function () {
    Route::put('driver/{id_driver}', 'App\Http\Controllers\Api\DriverController@update');
});

Route::get('customer/', 'App\Http\Controllers\Api\CustomerController@index');
Route::put('verify-customer/{id_customer}', 'App\Http\Controllers\Api\CustomerController@verifikasiPendaftaran');
//Route::put('verify-customer/{customer_id}', 'App\Http\Controllers\Api\CustomerController@update');



//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
