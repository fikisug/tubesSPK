<?php

use App\Http\Controllers\CriteriaController;
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
    return view('welcome');
})->name('home');

Route::post('/add-criteria', [CriteriaController::class, 'addCriteria'])->name('addCriteria');

Route::post('/add-alternatif', [CriteriaController::class, 'addAlternatif'])->name('addAlternatif');

Route::get('/criteria-names', [CriteriaController::class, 'getCriteria'])->name('getCriteria');

