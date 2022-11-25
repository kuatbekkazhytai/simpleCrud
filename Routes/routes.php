<?php

use App\Controllers\PrizeController;
use App\Routes\Route;
use App\Controllers\AuthController;
use App\Controllers\EmployeeController;

Route::add('GET', '/employees', [EmployeeController::class, 'index']);
Route::add('GET', '/employees/{id}', [EmployeeController::class, 'show']);
Route::add('POST', '/employees/create', [EmployeeController::class, 'store']);
Route::add('PUT', '/employees/update', [EmployeeController::class, 'update']);
Route::add('DELETE', '/employees/delete', [EmployeeController::class, 'delete']);

Route::add('POST', '/users/register', [AuthController::class, 'register']);
Route::add('POST', '/users/login', [AuthController::class, 'login']);
Route::add('GET', '/users/get', [AuthController::class, 'getUser']);

Route::add('GET', '/prize/draw', [PrizeController::class, 'getPrize']);
