<?php

include_once dirname(__FILE__) . '/RouteParser.php';

use App\config\RouteParser;

RouteParser::add("POST", "/employees/create","/api/create.php");
RouteParser::add("GET", "/employees/{id}","/api/single_read.php");
RouteParser::add("GET", "/employees","/api/read.php");
RouteParser::add("PUT", "/employees/update","/api/update.php");
RouteParser::add("DELETE", "/employees/delete","/api/delete.php");
