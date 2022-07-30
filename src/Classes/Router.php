<?php

namespace Classes;

use Interfaces\IRequest;

class Router
{
  private $request;
  private $supportedHttpMethods = array(
    "GET",
    "POST"
  );

  function __construct(IRequest $request)
  {
    $this->request = $request;
  }

  function __call($name, $args)
  {
    list($route, $method) = $args;

    if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
      $this->invalidMethodHandler();
    }

    $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
  }

  /**
   * Removes trailing forward slashes from the right of the route.
   * @param route (string)
   */
  private function formatRoute($route)
  {
    if ($_SERVER['HTTP_HOST'] === "localhost" || $_SERVER['HTTP_HOST'] === "127.0.0.1") {
      $route = str_replace("/" . getenv("APP_URL"), '', $route);
    }
    $result = rtrim($route, '/');
    if ($result === '') {
      return '/';
    }
    return $result;
  }

  private function invalidMethodHandler()
  {
    header("{$this->request->serverProtocol} 405 Method Not Allowed");
  }

  private function defaultRequestHandler()
  {
    header("{$this->request->serverProtocol} 404 Not Found");
  }

  /**
   * Resolves a route
   */
  function resolve()
  {
    $methodDictionary = $this->{strtolower($this->request->requestMethod)};
    $formatedRoute = $this->formatRoute($this->request->requestUri);
    if (!array_key_exists($formatedRoute, $methodDictionary)) {
      return $this->page_not_found();
    } else {
      $method = $methodDictionary[$formatedRoute];
    }

    if (is_null($method)) {
      $this->defaultRequestHandler();
      return;
    }

    echo call_user_func_array($method, array($this->request));
  }

  function page_not_found()
  {
    return view("404");
  }

  function __destruct()
  {
    $this->resolve();
  }
}
