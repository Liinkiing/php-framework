<?php

namespace Tests\Framework;

use Framework\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class RouterTest extends TestCase {


    private $router;

    public function __construct()
    {
        parent::__construct();
        $this->router = new Router();
    }

    public function testGetMethod() {
        $request = new ServerRequest("GET", "/blog");
        $this->router->get('/blog', function() { return "blog content"; }, "blog");
        $route = $this->router->match($request);
        $this->assertEquals("blog", $route->getName());
        $this->assertEquals("blog content", call_user_func($route->getCallback(), [$request]));
    }

    public function testGetMethodIfNotExist() {
        $request = new ServerRequest("GET", "/blog");
        $this->router->get('/blog-inexistant', function() { return "blog content"; }, "blog");
        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParams() {
        $request = new ServerRequest("GET", "/blog/article-1");
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function() { return "article content"; }, "article.show");
        $route = $this->router->match($request);
        $this->assertEquals("article content", call_user_func($route->getCallback(), [$request]));
        $this->assertEquals(["slug" => "article", "id" => "1"], $route->getParameters());
    }

    public function testGenerateUri() {
        $this->router->get("/chemin-de-test", function() {}, "test");
        $this->assertEquals("/chemin-de-test", $this->router->generateUri("test"));
    }

    public function testGenerateFalseUri() {
        $this->router->get("/chemin-de-test", function() {}, "test");
        $this->assertNotEquals("/blabla", $this->router->generateUri("test"));
    }

    public function testGenerateUriWithParams() {
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function() {}, "article.show");
        $this->assertEquals("/blog/article-1", $this->router->generateUri("article.show", ["slug" => "article", "id" => 1]));
    }



}