<?php

namespace Tests\Framework;

use App\Blog\BlogModule;
use Framework\App;
use Framework\Exceptions\BadResponseType;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tests\Framework\Modules\FakeModule;

class AppTest extends TestCase
{
    public function testRedirectTrailingSlash()
    {
        $app = new App([]);
        $request = new ServerRequest('GET', '/blog/');
        $response = $app->run($request);
        $this->assertSame(301, $response->getStatusCode());
        $this->assertContains('/blog', $response->getHeader('Location'));
    }

    public function testBlog()
    {
        $app = new App([
            BlogModule::class
        ]);
        $request = new ServerRequest('GET', '/blog');
        $response = $app->run($request);
        $this->assertContains('<h1>Liste des articles</h1>', (string) $response->getBody());
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testFalseModule() {
        $app = new App([
            FakeModule::class
        ]);
        $request = new ServerRequest('GET', '/test');
        $this->expectException(BadResponseType::class);
        $app->run($request);
    }

    public function testStringResponse() {
        $app = new App([
            FakeModule::class
        ]);
        $request = new ServerRequest("GET", "/string");
        $response = $app->run($request);
        $this->assertContains("<p>Je suis une chaîne de caractère</p>", (string) $response->getBody());
    }

    public function test404Error()
    {
        $app = new App([]);
        $request = new ServerRequest('GET', '/jesuisinexistantpromis');
        $response = $app->run($request);
        $this->assertContains('<h1>Erreur 404</h1>', (string) $response->getBody());
        $this->assertSame(404, $response->getStatusCode());
    }
}
