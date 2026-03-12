<?php
declare(strict_types=1);

class Router
{
    private array $routes = [];
    private string $groupPrefix = '';
    private string $currentMiddleware = '';

    public function get(string $path, string $handler): self
    {
        return $this->registerRoute('GET', $path, $handler);
    }

    public function post(string $path, string $handler): self
    {
        return $this->registerRoute('POST', $path, $handler);
    }

    public function middleware(string $name): self
    {
        // Update the last registered route's middleware
        if (!empty($this->routes)) {
            $lastIndex = count($this->routes) - 1;
            $this->routes[$lastIndex]['middleware'] = $name;
        }
        return $this;
    }

    public function group(string $prefix, callable $callback): void
    {
        $previousPrefix = $this->groupPrefix;
        $this->groupPrefix = $prefix;

        call_user_func($callback, $this);

        $this->groupPrefix = $previousPrefix;
    }

    private function registerRoute(string $method, string $path, string $handler): self
    {
        $fullPath = $this->groupPrefix ? $this->groupPrefix . $path : $path;

        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'handler' => $handler,
            'middleware' => $this->currentMiddleware,
        ];

        $this->currentMiddleware = '';
        return $this;
    }

    public function dispatch(): void
    {
        // Try ?url= param first (Apache .htaccess), fall back to REQUEST_URI
        if (!empty($_GET['url'])) {
            $url = $_GET['url'];
        } else {
            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        }
        $url = trim($url, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = [];
            $match = $this->matchRoute($route['path'], $url, $params);

            if ($match) {
                if ($route['middleware']) {
                    $this->applyMiddleware($route['middleware']);
                }

                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Page Not Found';
    }

    private function matchRoute(string $pattern, string $url, array &$params): bool
    {
        $params = [];

        if ($pattern === $url) {
            return true;
        }

        $patternParts = explode('/', trim($pattern, '/'));
        $urlParts = explode('/', $url);

        if (count($patternParts) !== count($urlParts)) {
            return false;
        }

        foreach ($patternParts as $i => $part) {
            if (preg_match('/^{(\w+)}$/', $part, $matches)) {
                $params[$matches[1]] = $urlParts[$i];
            } elseif ($part !== $urlParts[$i]) {
                return false;
            }
        }

        return true;
    }

    private function applyMiddleware(string $middleware): void
    {
        $middlewareFile = BASE_PATH . '/app/Middleware/' . ucfirst($middleware) . 'Middleware.php';

        if (file_exists($middlewareFile)) {
            require_once $middlewareFile;

            $class = ucfirst($middleware) . 'Middleware';

            if (class_exists($class)) {
                $middlewareInstance = new $class();
                $middlewareInstance->handle();
            }
        }
    }

    private function callHandler(string $handler, array $params): void
    {
        list($controllerName, $methodName) = explode('@', $handler);

        $controllerFile = BASE_PATH . '/app/Controllers/' . str_replace('\\', '/', $controllerName) . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo "Controller not found: $controllerName";
            return;
        }

        require_once $controllerFile;

        // Extract class name from namespace (e.g., Admin\DashboardController -> DashboardController)
        $parts = explode('\\', $controllerName);
        $className = end($parts);

        if (!class_exists($className)) {
            http_response_code(500);
            echo "Class not found: $className";
            return;
        }

        $controller = new $className();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo "Method not found: $methodName in $className";
            return;
        }

        // Match route params (named like 'id') to method parameters and cast types
        $reflection = new ReflectionMethod($controller, $methodName);
        $castParams = [];

        foreach ($reflection->getParameters() as $refParam) {
            $name = $refParam->getName();

            // Look up by parameter name first, then by position
            if (isset($params[$name])) {
                $value = $params[$name];
            } else {
                // No matching param — stop
                break;
            }

            $type = $refParam->getType();

            if ($type !== null && $type instanceof ReflectionNamedType && $type->isBuiltin()) {
                $typeName = $type->getName();
                if ($typeName === 'int') {
                    $castParams[] = (int) $value;
                } elseif ($typeName === 'float') {
                    $castParams[] = (float) $value;
                } elseif ($typeName === 'bool') {
                    $castParams[] = (bool) $value;
                } else {
                    $castParams[] = $value;
                }
            } else {
                $castParams[] = $value;
            }
        }

        call_user_func_array([$controller, $methodName], $castParams);
    }
}
