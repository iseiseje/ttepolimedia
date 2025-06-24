<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentSignatureController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('landing_astro');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/signatures', [DocumentSignatureController::class, 'index'])->name('signatures.index');
    Route::get('/signatures/create', [DocumentSignatureController::class, 'create'])->name('signatures.create');
    Route::post('/signatures', [DocumentSignatureController::class, 'store'])->name('signatures.store');
    Route::post('/signatures/{signature}/sign', [DocumentSignatureController::class, 'sign'])->name('signatures.sign');
    Route::post('/signatures/{signature}/reject', [DocumentSignatureController::class, 'reject'])->name('signatures.reject');
    Route::get('/signatures/{signature}/download', [DocumentSignatureController::class, 'download'])->name('signatures.download');
    Route::get('/signatures/{signature}/download-qr', [DocumentSignatureController::class, 'downloadQrCode'])->name('signatures.download-qr');
    Route::get('/signatures/{signature}/download-verified-qr', [DocumentSignatureController::class, 'downloadVerifiedQrCode'])->name('signatures.download-verified-qr');
    Route::get('/signatures/{signature}/sign-preview', [DocumentSignatureController::class, 'signPreview'])->name('signatures.sign-preview');
    Route::post('/signatures/{signature}/sign-finalize', [DocumentSignatureController::class, 'signFinalize'])->name('signatures.sign-finalize');
    Route::post('/signatures/{signature}/approve-qr', [DocumentSignatureController::class, 'approveQrPlacement'])->name('signatures.approve-qr');
});

Route::get('/signatures/{signature}/sign-as-guest', [DocumentSignatureController::class, 'signAsGuest'])->name('signatures.sign-as-guest');
Route::post('/signatures/{signature}/sign-finalize-as-guest', [DocumentSignatureController::class, 'signFinalizeAsGuest'])->name('signatures.sign-finalize-as-guest');

Route::get('/verification/{unique_code}', [VerificationController::class, 'show'])->name('verification.show');
Route::get('/verification/{unique_code}/download', [VerificationController::class, 'download'])->name('verification.download');

// Route::middleware(['auth', 'adminrole'])->group(function () {
//     Route::resource('users', UserController::class);
// });
Route::resource('users', UserController::class);

require __DIR__.'/auth.php';
