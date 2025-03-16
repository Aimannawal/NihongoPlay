<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VocabularyController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

Route::get('/', [VocabularyController::class, 'index'])->name('vocabularies.index');
Route::get('/category/{category}', [VocabularyController::class, 'showCategory'])->name('vocabularies.category');
Route::get('/vocabulary/create', [VocabularyController::class, 'create'])->name('vocabularies.create');
Route::post('/vocabulary', [VocabularyController::class, 'store'])->name('vocabularies.store');
Route::get('/barcode/{id}', [VocabularyController::class, 'getBarcode'])->name('vocabularies.barcode');
Route::post('/scan', [VocabularyController::class, 'scanBarcode'])->name('vocabularies.scan');
Route::get('/cards', [VocabularyController::class, 'generateCards'])->name('vocabularies.cards');
Route::get('/quiz', [VocabularyController::class, 'quiz'])->name('vocabularies.quiz');
Route::post('/quiz/questions', [VocabularyController::class, 'getQuizQuestions'])->name('vocabularies.getQuizQuestions');