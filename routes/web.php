<?php

use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Profile\UserProfileController;
use App\Http\Controllers\Admin\Time\TimeController;
use App\Http\Controllers\Admin\User\UsersController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/execute-command', function () {
//    Artisan::call('storage:link');
    Artisan::call('migrate:fresh --seed');
    Artisan::call('queue:work');
    echo 'All commands executed successfully with queue';
//    dd('All commands executed successfully');
});
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


//all routes for admin
Route::prefix('admin')->as('admin.')->group(function () {
    // USER
    Route::resource('users', UsersController::class);
    // PROFILE
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.info');
    Route::post('/avatar/update', [UserProfileController::class, 'avatarUpdate'])->name('avatar.update');
    Route::put('/profile/update/{id}', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/pass/update/', [UserProfileController::class, 'updatePassword'])->name('update.password');

    //CUSTOMER
    Route::resource('customers',CustomerController::class);
    Route::get('customers/find/in-radius',[CustomerController::class,'findCustomerInRadiusIndex'])->name('customers.findInRadiusGet');
    Route::post('customers/find/in-radius',[CustomerController::class,'findCustomerInRadiusPost'])->name('customers.findInRadiusPost');
    Route::post('customers/excel/file-import',[CustomerController::class,'importCustomerToExcel'])->name('customers.importCustomerToExcel');
    Route::get('customers/excel/progress/{id}',[CustomerController::class,'getUploadProgress'])->name('customers.getUploadProgress');
    Route::get('customers/excel/download-sample-file',[CustomerController::class,'downloadSampleFile'])->name('customers.downloadSampleFile');

    //timeshare
    Route::resource('times',TimeController::class);
    Route::post('times/search/prospects',[TimeController::class,'filter'])->name('times.search.prospect');
    Route::post('times/search/suppressed',[TimeController::class,'suppressed'])->name('times.search.suppressed');
    Route::get('times/search/download/{id}',[TimeController::class,'download'])->name('times.search.download');
    Route::get('times/customer/count',[TimeController::class,'countAllCustomer'])->name('times.customer.count');

});

//all routes for manager
Route::prefix('manager')->as('manager.')->group(function () {

});

//all routes for HR
Route::prefix('hr')->as('hr.')->group(function () {

});

//all routes for Employee
Route::prefix('employee')->as('employee.')->group(function () {

});


