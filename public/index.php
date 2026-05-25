<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\TaskStore;

$router = new Router();
$store = new TaskStore();

$router->get('/', function () {
    $viewFile = __DIR__ . '/../src/views/home.php';
    ob_start();
    require $viewFile;
    return ob_get_clean();
});

$router->get('/api/tasks', function () use ($store) {
    return $store->all();
});

$router->post('/api/tasks', function () use ($store) {
    $input = json_decode(file_get_contents('php://input'), true);
    $title = trim($input['title'] ?? '');

    if ($title === '') {
        http_response_code(400);
        return ['error' => 'Title is required'];
    }

    return $store->add($title);
});

$router->post('/api/tasks/toggle', function () use ($store) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($input['id'] ?? 0);

    $task = $store->toggle($id);
    if ($task === null) {
        http_response_code(404);
        return ['error' => 'Task not found'];
    }

    return $task;
});

$router->post('/api/tasks/delete', function () use ($store) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($input['id'] ?? 0);

    if (!$store->delete($id)) {
        http_response_code(404);
        return ['error' => 'Task not found'];
    }

    return ['success' => true];
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$result = $router->resolve($method, $uri);

if (is_string($result)) {
    echo $result;
} else {
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
