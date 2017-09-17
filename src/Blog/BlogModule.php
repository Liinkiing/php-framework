<?php

namespace App\Blog;

use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{

    public function __construct(Router $router)
    {
        $router->get("/blog", [$this, "index"], "blog.index");
        $router->get("/blog/{slug}", [$this, "show"], "blog.show");
    }


    public function index(ServerRequestInterface $request)
    {
        return new Response(200, [], "<h1>Liste des articles</h1>");
    }

    public function show(ServerRequestInterface $request)
    {
        return "test";
    }
}