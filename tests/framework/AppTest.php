<?php

use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testRedirectTrailingSlash()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/blog/');
        $response = $app->run($request);
        $this->assertSame(301, $response->getStatusCode());
        $this->assertContains('/blog', $response->getHeader('Location'));
    }

    public function testBlog()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/blog');
        $response = $app->run($request);
        $this->assertContains('<h1>Bienvenue sur le blog</h1>', (string) $response->getBody());
        $this->assertSame(200, $response->getStatusCode());
    }

    public function test404Error()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/jesuisinexistantpromis');
        $response = $app->run($request);
        $this->assertContains('<h1>Erreur 404</h1>', (string) $response->getBody());
        $this->assertSame(404, $response->getStatusCode());
    }
}
