<?php

use Illuminate\Support\Facades\Route;

/* oute::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
}); */
Route::get('/', fn() => redirect('/admin'));

Route::get('login', function () {
    return redirect('https://woombi.com.ar');
});

Route::get('/docs/ApiDocumentation.md', function () {
    return response()->download(base_path('docs/ApiDocumentation.md'));
})->name('downloadDocs');

