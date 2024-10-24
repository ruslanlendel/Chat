<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsoleController;
use Pusher\Pusher;
use Illuminate\Http\Request;

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

Route::get('/', [ConsoleController::class, 'index']);
Route::post('/typing', [ConsoleController::class, 'sendTyping']);
Route::post('/command', [ConsoleController::class, 'sendCommand']);