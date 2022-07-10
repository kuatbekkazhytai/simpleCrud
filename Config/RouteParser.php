<?php

namespace App\Config;

class RouteParser {
    /**
     * @param string $method
     * @param string $route
     * @param array $pathToMethod
     * @return void
     */
    public static function add(string $method, string $route, array $pathToMethod): void {

        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            return;
        }
        //remove App from controller namespace and add .php
        $includes = str_replace('\\', '/', substr($pathToMethod[0], 3) . '.php');
        include_once dirname(__FILE__, 2) . $includes;

        //will store all the parameters value in this array
        $params = [];

        //will store all the parameters names in this array
        $paramKey = [];

        //finding if there is any {?} parameter in $route
        preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);

        //if the route does not contain any param call simpleRoute();
        if (empty($paramMatches[0])) {
            self::simpleRoute($pathToMethod, $route);
            return;
        }

        //setting parameters names
        foreach($paramMatches[0] as $key) {
            $paramKey[] = $key;
        }

        //replacing first and last forward slashes
        //$_REQUEST['uri'] will be empty if req uri is /
        if (!empty($_SERVER['REQUEST_URI'])) {
            $route = preg_replace("/(^\/)|(\/$)/","", $route);
            $reqUri =  preg_replace("/(^\/)|(\/$)/","", $_SERVER['REQUEST_URI']);
        } else {
            $reqUri = '/';
        }

        //exploding route address
        $uri = explode('/', $route);

        //will store index number where {?} parameter is required in the $route
        $indexNum = [];

        //storing index number, where {?} parameter is required with the help of regex
        foreach($uri as $index => $param){
            if(preg_match("/{.*}/", $param)) {
                $indexNum[] = $index;
            }
        }

        //exploding request uri string to array to get
        //the exact index number value of parameter from $_REQUEST['uri']
        $reqUri = explode('/', $reqUri);

        //running for each loop to set the exact index number with reg expression
        //this will help in matching route
        foreach($indexNum as $key => $index) {

            //in case if req uri with param index is empty then return
            //because url is not valid for this route
            if(empty($reqUri[$index])) {
                return;
            }

            //setting params with params names
            $params[$paramKey[$key]] = $reqUri[$index];

            //this is to create a regex for comparing route address
            $reqUri[$index] = "{.*}";
        }

        //converting array to string
        $reqUri = implode('/', $reqUri);

        //replace all / with \/ for reg expression
        //regex to match route is ready !
        $reqUri = str_replace('/', '\\/', $reqUri);

        //now matching route with regex
        if (preg_match("/$reqUri/", $route)) {
            $class = new $pathToMethod[0];
            $function = $pathToMethod[1];
            $class->$function($params);
            exit();
        }
    }

    /**
     * @param array $pathToMethod
     * @param string $route
     * @return void
     */
    private static function simpleRoute(array $pathToMethod, string $route): void {
        //replacing first and last forward slashes
        //$_REQUEST['uri'] will be empty if req uri is /

        if (!empty($_SERVER['REQUEST_URI'])) {
            $route = preg_replace("/(^\/)|(\/$)/","",$route);
            $reqUri =  preg_replace("/(^\/)|(\/$)/","",$_SERVER['REQUEST_URI']);
        } else {
            $reqUri = '/';
        }

        if ($reqUri == $route) {
            $class = new $pathToMethod[0];
            $function = $pathToMethod[1];
            $class->$function();
            exit();
        }
    }
}
