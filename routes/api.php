<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

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
Route::post('/auth', [AuthController::class, 'auth']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['prefix' => 'auth', 'middleware' => 'jwt'], function () {

    Route::post('createTask', [TaskController::class, 'createTask'])->name('createTask');
    Route::post('updateTask', [TaskController::class, 'updateTask']);
    Route::get('showTask/{id}', [TaskController::class, 'showTask']);
    Route::get('deleteTask/{id}', [TaskController::class, 'deleteTask']);
    Route::get('getTasks', [TaskController::class, 'getTasks']);

});

  