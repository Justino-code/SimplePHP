<?php
namespace SPHP\Http;

use Closure;
use ReflectionMethod;

/**
 * Classe responsável por registrar e despachar rotas HTTP.
 */
class Router
{
    /**
     * Lista de rotas registradas organizadas por método HTTP.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Lista de rotas nomeadas.
     *
     * @var array
     */
    private array $namedRoutes = [];

    /**
     * Registra uma rota do tipo GET.
     *
     * @param string $uri URI da rota (ex: '/home')
     * @param callable|array $action Função anônima ou [Controller::class, 'método']
     * @param array $middlewares Lista de middlewares (nomes de classes)
     * @param string|null $name Nome opcional da rota
     * @return void
     */
    public function get(string $uri, callable|array $action, array $middlewares = [], ?string $name = null): void
    {
        $this->addRoute('GET', $uri, $action, $middlewares, $name);
    }

    /**
     * Registra uma rota do tipo POST.
     *
     * @param string $uri
     * @param callable|array $action
     * @param array $middlewares
     * @param string|null $name
     * @return void
     */
    public function post(string $uri, callable|array $action, array $middlewares = [], ?string $name = null): void
    {
        $this->addRoute('POST', $uri, $action, $middlewares, $name);
    }

    /**
     * Adiciona uma nova rota ao sistema.
     *
     * @param string $method Método HTTP (GET, POST, etc.)
     * @param string $uri URI da rota
     * @param callable|array $action Função anônima ou [Controller::class, 'método']
     * @param array $middlewares Middlewares a aplicar
     * @param string|null $name Nome opcional da rota
     * @return void
     * @throws \Exception
     */
    private function addRoute(string $method, string $uri, callable|array $action, array $middlewares, ?string $name = null): void
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
     * Extrai os nomes dos parâmetros definidos na URI.
     *
     * @param string $uri
     * @return array Lista de nomes dos parâmetros
     */
    private function extractParamNames(string $uri): array
    {
        preg_match_all('#\{([\w]+)\}#', $uri, $matches);
        return $matches[1];
    }

    /**
     * Processa e despacha a requisição atual para a rota correspondente.
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
     * Executa a ação da rota (controller ou função anônima).
     *
     * @param callable|array $action
     * @param Request $request
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private function callAction(callable|array $action, Request $request, array $params = [])
    {
        if (is_callable($action)) {
            return call_user_func_array($action, [$request, ...array_values($params)]);
        }

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
     * Monta o pipeline de execução dos middlewares.
     *
     * @param array $middlewares Lista de middlewares (nomes de classes)
     * @param callable $core Função principal (a rota em si)
     * @return callable Pipeline final
     * @throws \Exception
     */
    private function buildMiddlewarePipeline(array $middlewares, callable $core): callable
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
     * Gera uma URL com base em uma rota nomeada e parâmetros.
     *
     * @param string $name Nome da rota
     * @param array $params Parâmetros a substituir na URI
     * @return string
     * @throws \Exception
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
