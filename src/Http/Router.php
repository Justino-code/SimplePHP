<?php
namespace Src;

use ReflectionMethod;

class Router
{
    /**
     * @var array Lista de rotas registradas por método HTTP.
     */
    private array $routes = [];

    /**
     * @var array Lista de rotas nomeadas.
     */
    private array $namedRoutes = [];

    /**
     * Registra uma rota do tipo GET.
     *
     * @param string $uri
     * @param array $action [ControllerClass::class, 'método']
     * @param array $middlewares
     * @param string|null $name
     * @return void
     */
    public function get(string $uri, array $action, array $middlewares = [], ?string $name = null)
    {
        $this->addRoute('GET', $uri, $action, $middlewares, $name);
    }

    /**
     * Registra uma rota do tipo POST.
     */
    public function post(string $uri, array $action, array $middlewares = [], ?string $name = null)
    {
        $this->addRoute('POST', $uri, $action, $middlewares, $name);
    }

    /**
     * Adiciona uma nova rota ao roteador.
     */
    private function addRoute(string $method, string $uri, array $action, array $middlewares, ?string $name = null)
    {
        $uri = '/' . trim($uri, '/');

        foreach ($this->routes[$method] ?? [] as $route) {
            if ($route['uri'] === $uri) {
                throw new \Exception("Rota duplicada detectada: método '{$method}' e URI '{$uri}' já estão registrados.");
            }
        }

        $pattern = preg_replace('#\{([\w]+)\}#', '([\w-]+)', $uri);
        $pattern = "#^" . rtrim($pattern, '/') . "/?$#";

        $route = [
            'uri'         => $uri,
            'pattern'     => $pattern,
            'action'      => $action,
            'middlewares' => $middlewares,
            'params'      => $this->extractParamNames($uri)
        ];

        $this->routes[$method][] = $route;

        if ($name) {
            if (isset($this->namedRoutes[$name])) {
                throw new \Exception("Nome de rota duplicado: '{$name}' já está em uso.");
            }
            $this->namedRoutes[$name] = $uri;
        }
    }

    /**
     * Extrai os nomes dos parâmetros de uma URI.
     *
     * @param string $uri
     * @return array
     */
    private function extractParamNames(string $uri): array
    {
        preg_match_all('#\{([\w]+)\}#', $uri, $matches);
        return $matches[1];
    }

    /**
     * Despacha a requisição atual para a rota correta.
     *
     * @param string $requestUri
     * @param string $requestMethod
     * @return mixed
     */
    public function dispatch(string $requestUri, string $requestMethod)
    {
        $uri = rtrim(parse_url($requestUri, PHP_URL_PATH), '/') ?: '/';

        foreach ($this->routes[$requestMethod] ?? [] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                $params = array_combine($route['params'], $matches) ?: [];

                $middlewarePipeline = $this->buildMiddlewarePipeline(
                    $route['middlewares'],
                    function ($request) use ($route, $params) {
                        return $this->callAction($route['action'], $request, $params);
                    }
                );

                return $middlewarePipeline(new Request());
            }
        }

        http_response_code(404);
        echo "404 - Rota não encontrada";
    }

    /**
     * Executa a ação de um controller.
     */
    private function callAction(array $action, Request $request, array $params = [])
    {
        [$controllerClass, $method] = $action;

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} não encontrado.");
        }

        $controller = new $controllerClass();
        $refMethod = new ReflectionMethod($controller, $method);

        $args = [];
        foreach ($refMethod->getParameters() as $param) {
            $type = $param->getType();
            if ($type && $type->getName() === Request::class) {
                $args[] = $request;
            } else {
                $args[] = $params[$param->getName()] ?? null;
            }
        }

        return $refMethod->invokeArgs($controller, $args);
    }

    /**
     * Cria o pipeline de middlewares.
     *
     * @param array $middlewares
     * @param callable $core
     * @return callable
     */
    private function buildMiddlewarePipeline(array $middlewares, callable $core)
    {
        return array_reduce(
            array_reverse($middlewares),
            function ($next, $middlewareClass) {
                return function ($request) use ($middlewareClass, $next) {
                    if (!class_exists($middlewareClass)) {
                        throw new \Exception("Middleware {$middlewareClass} não encontrado.");
                    }

                    $middleware = new $middlewareClass();

                    if (!method_exists($middleware, 'handle')) {
                        throw new \Exception("Middleware {$middlewareClass} deve ter um método handle(Request \$request, callable \$next)");
                    }

                    return $middleware->handle($request, $next);
                };
            },
            $core
        );
    }

    /**
     * Gera uma URL a partir de uma rota nomeada.
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function route(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new \Exception("Rota nomeada '{$name}' não encontrada.");
        }

        $uri = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $uri = str_replace("{{$key}}", $value, $uri);
        }

        return '/' . ltrim($uri, '/');
    }
}
