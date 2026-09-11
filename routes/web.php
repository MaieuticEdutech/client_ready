<?php

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'services' => Service::published()->inOrder()->with('samples')->get(),
    ]);
})->name('home');

/*
|--------------------------------------------------------------------------
| Studio (admin)
|--------------------------------------------------------------------------
| Accounts are created by an administrator (`php artisan studio:user`).
| There is deliberately no public registration route.
*/

Route::prefix('studio')->name('admin.')->group(function () {
    Route::view('/login', 'admin.login')->middleware('guest')->name('login');

    Route::middleware('auth')->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::view('/samples', 'admin.samples')->name('samples');

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home');
        })->name('logout');
    });
});
