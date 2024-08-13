<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
return Auth::user();
});

Route::middleware(['auth'])->group(function () {
    Route::resource('genres', GenreController::class);
    Route::resource('artists', ArtistController::class);
    Route::get('artists-export', [ArtistController::class,'export'])
    ->name('artists.export');
    Route::post('artists-import', [ArtistController::class,'import'])
        ->name('artists.import');
    Route::resource('musics', MusicController::class);
    Route::resource('users', UserController::class);
    Route::get('dashboard', function () {
        return 'test';
    })->name('admin.dashboard');
    Route::get('logout',function ()  {
        Auth::logout();
        return redirect("/login");
    })->name('logout');
});

Route::get("login/",[UserController::class,"login_index"])->name("login");
Route::post("login/",[UserController::class,"login"])->name("user.login");
Route::get("register/",[UserController::class,"register_index"])->name("user.register-form");
Route::post("register/",[UserController::class,"register"])->name("user.register");
