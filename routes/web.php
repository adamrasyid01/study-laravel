<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileInformationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::get('/', HomeController::class);

Route::get('contact',[ContactController::class,'create']);
Route::post('contact',[ContactController::class,'store']);
Route::get('profile/{identifier}', ProfileInformationController::class);
Route::get('tasks',[TaskController::class, 'index']);
Route::post('tasks', [TaskController::class, 'store']); 