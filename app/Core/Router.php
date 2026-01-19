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

        // Base path fix
        $basePath = '/api-todo';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        if (!isset($this->routes[$method])) {
            Response::json(['error' => 'Route not found'], 404);
            return;
        }

        foreach ($this->routes[$method] as $route => $action) {

            // Convert /todos/{id} to regex
            $pattern = preg_replace('#\{id\}#', '([0-9]+)', $route);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches); // remove full match
                [$class, $methodName] = $action;

                $controller = new $class();
                $controller->$methodName(...$matches);
                return;
            }
        }

        Response::json(['error' => 'Route not found'], 404);
    }
}