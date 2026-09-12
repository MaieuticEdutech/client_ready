<?php

use App\Http\Controllers\FilmController;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'services' => Service::published()->inOrder()->with('samples')->get(),
    ]);
})->name('home');

// Films on a local disk stream through PHP so byte ranges work everywhere,
// including the built-in dev server, which ignores Range on static files.
Route::get('/films/{path}', FilmController::class)
    ->where('path', '[A-Za-z0-9._-]+')
    ->name('films.show');

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
