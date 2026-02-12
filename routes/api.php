<?php
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/TodoController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

$router = new Router();


// Auth endpoint
// register new user
$router->post('/register', [AuthController::class, 'register']);

// Login registered user
$router->post('/login', [AuthController::class, 'login']);


// Todo endpoints

// Route: http://localhost/api-todo/todos

// Read all todos by an authorized user
$router->get('/todos', [TodoController::class, 'userTodo'], ['auth']);


// Create a new todo for an authorized user
$router->post('/todos', [TodoController::class, 'store'], ['auth']);

// view or display todo by an uthorize user
$router->get('/todos/{id}', [TodoController::class, 'getSingleTodo'], ['auth']);

// Update a todo by an authorized user
$router->put('/todos/{id}', [TodoController::class, 'update'], ['auth']);

// Delete a todo by ID
$router->delete('/todos/{id}', [TodoController::class, 'destroy'], ['auth']);

$router->dispatch();
