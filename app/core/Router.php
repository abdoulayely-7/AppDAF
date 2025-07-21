<?php

namespace app\core;

class Router
{
  public static function resolve(array $tabs)
  {
    $uri = $_SERVER["REQUEST_URI"];
    if (array_key_exists($uri, $tabs)) {
      $controllerName = $tabs[$uri]['controller'];
      $action = $tabs[$uri]['action'];
      $middlewares = require '../app/config/middlewares.php';
      $middlewareName = $tabs[$uri]['middleware'] ?? null;
      if (isset($middlewares[$middlewareName])) {
        $middlewareClase = $middlewares[$middlewareName];
        $middleware = new $middlewareClase();
        $middleware();
      }
      $controller = new $controllerName();
      $controller->$action();
    } else {
      var_dump("erreur");
      die;
    }
  }
}
