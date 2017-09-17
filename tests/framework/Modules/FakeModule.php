<?php

namespace Tests\Framework\Modules;

use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FakeModule
{

    public function __construct(Router $router)
    {
       $router->get('/test', function() { return null; }, "test");
       $router->get('/string', function() { return "Je suis une chaîne de caractère"; }, "string");
    }


}