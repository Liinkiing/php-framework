<?php

namespace Framework\Router;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;

/**
 * Class Router
 * Register and match routes
 */
class Router
{

    private $router;


    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param string $path
     * @param callable $callback
     * @param string $name
     */
    public function get(string $path, callable $callback, string $name)
    {
        $this->router->addRoute(new \Zend\Expressive\Router\Route($path, $callback, ["GET"], $name));
    }


    /**
     * @param string $name
     * @param array|null $params
     * @return string|null
     */
    public function generateUri(string $name, ?array $params = []): ?string
    {
        return $this->router->generateUri($name, $params);
    }


    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $route = $this->router->match($request);
        if ($route->isFailure()) {
            return null;
        }
        return new Route(
            $route->getMatchedRouteName(),
            $route->getMatchedMiddleware(),
            $route->getMatchedParams()
        );
    }
}
