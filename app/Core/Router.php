<?php
require_once __DIR__ . '/AuthMiddleware.php';

// use App\Core\AuthMiddleware;


class Router
{
    private array $routes = [];

    public function get($uri, $action, $middleware = [])
    {
        $this->routes['GET'][$uri] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function post($uri, $action, $middleware = [])
    {
        $this->routes['POST'][$uri] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function put($uri, $action, $middleware = [])
    {
        $this->routes['PUT'][$uri] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function delete($uri, $action, $middleware = [])
    {
        $this->routes['DELETE'][$uri] = [
            'action' => $action,
            'middleware' => $middleware
        ];
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

        foreach ($this->routes[$method] as $route => $routeData) {

            // Convert /todos/{id} to regex
            $pattern = preg_replace('#\{id\}#', '([0-9]+)', $route);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches); // remove full match

                $action = $routeData['action'];
                $middleware = $routeData['middleware'] ?? [];

                // Run middleware if any
                if (!empty($middleware)) {
                    foreach ($middleware as $mid) {
                        if ($mid === 'auth') {
                            // stops execution if invalid
                            $user = AuthMiddleware::handle(); 
                            $GLOBALS['auth_user'] = $user; // make user available in controller
                        }
                    }
                }

                [$class, $methodName] = $action;
                $controller = new $class();
                $controller->$methodName(...$matches);
                return;
            }
        }

        Response::json(['error' => 'Route not found'], 404);
    }

}