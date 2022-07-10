<?php

include_once dirname(__FILE__) . '/RouteParser.php';

use App\config\RouteParser;
use App\Controllers\EmployeeController;

RouteParser::add('GET', '/employees',[EmployeeController::class, 'index']);
RouteParser::add('GET', '/employees/{id}',[EmployeeController::class, 'show']);
RouteParser::add('POST', '/employees/create',[EmployeeController::class, 'store']);
RouteParser::add('PUT', '/employees/update',[EmployeeController::class, 'update']);
RouteParser::add('DELETE', '/employees/delete',[EmployeeController::class, 'delete']);
