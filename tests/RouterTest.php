<?php

namespace Tests;

use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testGetRouteReturnsHandlerResult(): void
    {
        $router = new Router();
        $router->get('/hello', fn () => ['message' => 'Hello!']);

        $result = $router->resolve('GET', '/hello');

        $this->assertEquals(['message' => 'Hello!'], $result);
    }

    public function testPostRouteReturnsHandlerResult(): void
    {
        $router = new Router();
        $router->post('/submit', fn () => ['status' => 'ok']);

        $result = $router->resolve('POST', '/submit');

        $this->assertEquals(['status' => 'ok'], $result);
    }

    public function testUnknownRouteReturns404(): void
    {
        $router = new Router();

        $result = $router->resolve('GET', '/nonexistent');

        $this->assertEquals(['error' => 'Not Found'], $result);
    }

    public function testRouteWithQueryString(): void
    {
        $router = new Router();
        $router->get('/search', fn () => ['results' => []]);

        $result = $router->resolve('GET', '/search?q=test');

        $this->assertEquals(['results' => []], $result);
    }
}
