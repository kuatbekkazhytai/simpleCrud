<?php

namespace App\Routes;

//TODO optimize parsing. Add middleware

class Route
{
    /** @var array to store params */
    private static $params = [];
    /** @var array to store param keys */
    private static $paramKey = [];

    /**
     * @param string $method
     * @param string $route
     * @param array $pathToMethod
     * @return void
     */
    public static function add(string $method, string $route, array $pathToMethod): void {

        if ($_SERVER['REQUEST_METHOD'] !== $method) return;
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            self::$params['post'] = json_decode(file_get_contents('php://input'));
        }

        //check {?} parameter in $route
        preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);

        if (empty($paramMatches[0])) {
            self::simpleRoute($pathToMethod, $route);
        } else {
            self::complexRoute($paramMatches, $pathToMethod, $route);
        }
    }

    /**
     * @param array $pathToMethod
     * @param string $route
     * @return void
     */
    private static function simpleRoute(array $pathToMethod, string $route): void {
        //replacing first and last forward slashes
        //$_SERVER['REQUEST_URI'] will be empty if req uri is /
        if (!empty($_SERVER['REQUEST_URI'])) {
            $route = preg_replace("/(^\/)|(\/$)/","",$route);
            $reqUri =  preg_replace("/(^\/)|(\/$)/","",$_SERVER['REQUEST_URI']);
        } else {
            $reqUri = '/';
        }

        if ($reqUri == $route) {
            $class = new $pathToMethod[0];
            $function = $pathToMethod[1];
            $class->$function(self::$params);
            exit();
        }
    }

    /**
     * @param array $paramMatches
     * @param array $pathToMethod
     * @param string $route
     * @return void
     */
    private static function complexRoute(array $paramMatches, array $pathToMethod, string $route): void {
        //setting parameters names
        foreach($paramMatches[0] as $key) {
           self::$paramKey[] = $key;
        }

        //replacing first and last forward slashes
        //$_SERVER['REQUEST_URI'] will be empty if req uri is /
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
        foreach ($indexNum as $key => $index) {
            //in case if req uri with param index is empty then return
            //because url is not valid for this route
            if (empty($reqUri[$index])) {
                return;
            }
            //setting params with params names
           self::$params[self::$paramKey[$key]] = $reqUri[$index];

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
            $class->$function(self::$params);
            exit();
        }
    }
}
