<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student;

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
    return view('pages.login');
})->name('/');
Route::post('login', [Student::class, 'login'])->name('login');
Route::middleware(['auth'])->group(function () {
Route::get('/dashbord',[Student::class,'index'])->name('dashbord');
Route::post('/student', [Student::class, 'addStudent'])->name('student');
Route::get('/edit/{id}', [Student::class, 'editStudent'])->name('edit');
Route::get('/delete/{id}', [Student::class, 'deleteStudent'])->name('delete');
Route::get('/search', [Student::class, 'search'])->name('search');
});
Route::post('/logout', [Student::class, 'logout'])->name('logout');
