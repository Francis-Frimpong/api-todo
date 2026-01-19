<?php
require_once __DIR__ . '/../Models/Todo.php';
require_once __DIR__ . '/../Core/Response.php';

class TodoController
{
    private Todo $todo;

    public function __construct()
    {
        $this->todo = new Todo();
    }

    public function index()
    {
        $data = $this->todo->all();
        Response::json($data, 200);
    }

    public function store(){
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Check if JSON is valid
        if (!$data) {
            Response::json(['error' => 'Invalid JSON or empty body'], 400);
            return;
        }

        // Check required fields
        if (!isset($data['title']) || !isset($data['description'])) {
            Response::json(['error' => 'Title and description are required'], 400);
            return;
        }

        // Pass safe data to model
        $result = $this->todo->create($data);

        Response::json($result, 201);
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->todo->update($id,$input);
        Response::json($result, 200);
    }

    public function destroy($id)
    {
        $result = $this->todo->delete($id);
        Response::json($result, 200);
    }
}