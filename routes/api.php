<?php
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/TodoController.php';

$router = new Router();

// Route: http://localhost/api-todo/todos

// Read all todos
$router->get('/todos', [TodoController::class, 'index']);


// Create a new todo
$router->post('/todos', [TodoController::class, 'store']);

// view or display todo by Id
$router->get('/todos/{id}', [TodoController::class, 'indexById']);

// Update a todo by ID
$router->put('/todos/{id}', [TodoController::class, 'update']);

// Delete a todo by ID
$router->delete('/todos/{id}', [TodoController::class, 'destroy']);

$router->dispatch();
