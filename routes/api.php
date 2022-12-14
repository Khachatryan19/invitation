<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/sendInvitation', [UserController::class, 'index']);
Route::get('/getAll', [UserController::class, 'getAll']);

Route::post('getJsonData', [\App\Http\Controllers\ReaderController::class, 'index']);