<?php
class Router
{
    private array $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function put($uri, $action)
    {
        $this->routes['PUT'][$uri] = $action;
    }

    public function delete($uri, $action)
    {
        $this->routes['DELETE'][$uri] = $action;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


        // If your project folder is /api-todo
        $basePath = '/api-todo';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Remove trailing slash
        $uri = rtrim($uri, '/');

        // If URI is empty, set to /
        if ($uri === '') {
            $uri = '/';
        }

        if(!isset($this->routes[$method][$uri])){
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }



        [$class, $methodName] = $this->routes[$method][$uri];
        $controller = new $class();
        $controller->$methodName();
    }
}