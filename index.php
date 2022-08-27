<?php
declare(strict_types = 1);

require_once "route_handler.php";

function srcImports($class){
    include_once "src/$class.php";
}

spl_autoload_register('srcImports');

set_error_handler("ErrorHandler::handleError");

set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$request_uri = explode("/",$_SERVER["REQUEST_URI"]);

routeHandler($request_uri);
