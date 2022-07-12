<?php

use App\Config\Route;
use App\Controllers\EmployeeController;

Route::add('GET', '/employees', [EmployeeController::class, 'index']);
Route::add('GET', '/employees/{id}', [EmployeeController::class, 'show']);
Route::add('POST', '/employees/create', [EmployeeController::class, 'store']);
Route::add('PUT', '/employees/update', [EmployeeController::class, 'update']);
Route::add('DELETE', '/employees/delete', [EmployeeController::class, 'delete']);
