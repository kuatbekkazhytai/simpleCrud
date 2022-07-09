<?php

include_once dirname(__FILE__) . '/RouteParser.php';
include_once dirname(__FILE__) . '/TestRouteParser.php';

use App\config\RouteParser;
use App\config\TestRouteParser;
use App\Controllers\EmployeeController;

RouteParser::add('POST', '/employees/create','/api/create.php');
TestRouteParser::add('GET', '/employees',[EmployeeController::class, 'index']);
//RouteParser::add('GET', '/employees/{id}','/api/single_read.php');
TestRouteParser::add('GET', '/employees/{id}',[EmployeeController::class, 'show']);
//RouteParser::add('GET', '/employees','/api/read.php');
RouteParser::add('PUT', '/employees/update','/api/update.php');
RouteParser::add('DELETE', '/employees/delete','/api/delete.php');
