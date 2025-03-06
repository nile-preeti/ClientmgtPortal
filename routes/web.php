<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AttendanceCDontroller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\JobScheduleController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ServiceController;
use App\Models\Customer;
use Illuminate\Support\Facades\Artisan;
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
// bonvinant-love of food
//voracious- avid for something,f
Route::get("/", [UserController::class, 'login'])->name('home');
Route::get("/login", [UserController::class, 'login'])->name('login');
Route::get("/admin", [AdminController::class, 'signin'])->name('admin');
Route::get("/admin/login", [AdminController::class, 'signin'])->name('admin.login');
Route::post("signin_post", [AdminController::class, 'signin_post'])->name('signin.post');


Route::group(['middleware' => 'admin'], function () {
    Route::get("dashboard", [AdminController::class, 'dashboard'])->name("dashboard");
    Route::get("profile", [AdminController::class, 'profile'])->name("profile");
    Route::post("profile_post", [AdminController::class, 'profile_post'])->name("profile_post");

    Route::get("change_password", [AdminController::class, 'change_password'])->name("change_password");
    Route::post("change_password_post", [AdminController::class, 'change_password_post'])->name("change_password_post");


    Route::resource("userss", UserController::class);
    Route::resource("payouts", PayoutController::class);
    Route::resource("job_schedules", JobScheduleController::class);
    Route::get("users/job_schedule/{id}", [UserController::class, 'job_schedule'])->name("user.job_schedule");

    Route::resource("customers", CustomerController::class);

    Route::prefix("master")->as("master.")->group(function () {
        Route::resource("services", ServiceController::class);
    });
    Route::get("reports", [AdminController::class, 'reports'])->name("reports");
    Route::get("settings", [AdminController::class, 'settings'])->name("settings");
    Route::post("settings", [AdminController::class, 'settings_store'])->name("settings.store");


    Route::get("users/attendance/{id}", [UserController::class, 'userAttendance'])->name("userAttendance");

    //holidays
    Route::resource("holidayss", HolidayController::class);

    Route::get("logout", [AdminController::class, 'logout'])->name("logout");
});


Route::prefix('user')->as("user.")->group(function () {
    Route::get('/', [UserController::class, 'login'])->name('login'); // Route for storing attendance
    Route::post('/login', [UserController::class, 'login_post'])->name('login_post'); // Route for storing attendance

    Route::middleware("auth")->group(function () {
        Route::get("dashboard", [UserController::class, 'dashboard'])->name("dashboard");
        Route::get("attendance", [UserController::class, 'attendance'])->name('attendance');
        Route::get("profile", [UserController::class, 'profile'])->name("profile");
        Route::post('/attendance/store', [AjaxController::class, 'storeAttendance'])->name('attendance.store');

        // Route for updating attendance (Check-out)
        Route::post('/attendance/update', [AjaxController::class, 'updateAttendance'])->name('attendance.update');
        Route::get('/attendance/fetch', [AjaxController::class, 'fetchAttendance'])->name('attendance.fetch');
        Route::get('/attendance/fetch/today', [AjaxController::class, 'fetchAttendancetoday'])->name('attendance.fetch.today');
        Route::post('/attendance/break', [AjaxController::class, 'handleBreak'])->name('attendance.break');
        
        //user.attendance.fetch.today
        Route::get("attendance_records", [UserController::class, 'attendance_records'])->name("attendance_records");
        Route::post('/fetch-break-details', [AjaxController::class, 'fetchBreakDetails'])->name('fetch.break.details');
        Route::get("holidays", [UserController::class, 'holidays'])->name("holidays");
        Route::get("directory", [UserController::class, 'directory'])->name("directory");
        Route::get("all-emp-directory", [AjaxController::class, 'Employeedirectory'])->name("employee.directory");
        
        Route::get("services", [UserController::class, 'Services'])->name("services");
        Route::get("all-services", [AjaxController::class, 'userServices'])->name("employee.services");
// reports

        Route::post('/change-password', [UserController::class, 'changePassword'])->name('change.password');
        Route::get("logout", [UserController::class, 'logout'])->name("logout");
    });
});


Route::get("get-service", [AjaxController::class, 'getService'])->name("get_service");
Route::get("get-customer", [AjaxController::class, 'getCustomer'])->name("get_customer");


// Ajax Routes for image upload

Route::post("image-upload", [AjaxController::class, "uploadImage"])->name('image-upload');
Route::post("image-delete", [AjaxController::class, "deleteImage"])->name('image-delete');
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return "Cache is cleared";
});
