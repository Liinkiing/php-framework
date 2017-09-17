<?php

namespace Framework;

use Framework\Exceptions\BadResponseType;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    private $modules;

    private $router;

    /**
     * App constructor.
     * @param string[] $modules
     */
    public function __construct(array $modules)
    {
        $this->router = new Router();
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router);
        }
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (!empty($uri) && '/' === $uri[-1]) {
            $response = new Response();
            $response = $response
                ->withStatus(301)
                ->withHeader('Location', mb_substr($uri, 0, -1));
            return $response;
        }
        if (!$matched = $this->router->match($request)) {
            return new Response(404, $request->getHeaders(), '<h1>Erreur 404</h1>');
        } else {
            foreach ($matched->getParameters() as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }
            $response = call_user_func($matched->getCallback(), $request);
            if ($response instanceof ResponseInterface || is_string($response)) {
                if (is_string($response)) {
                    return new Response(200, $request->getHeaders(), "$response");
                } else {
                    return $response;
                }
            } else {
                throw new BadResponseType();
            }
        }
    }
}
