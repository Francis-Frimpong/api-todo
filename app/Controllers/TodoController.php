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
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->todo->create($input);
        Response::json($result, 200);
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