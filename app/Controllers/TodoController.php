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

    public function userTodo()
    {
        // Get the logged-in user via middleware
        // $user = AuthMiddleware::handle();
        $user = $GLOBALS['auth_user'] = AuthMiddleware::handle();


        // Fetch todos for this user
        $data = $this->todo->getTodoByUserID($user->id);

        Response::json($data, 200);
    }

    public function getSingleTodo($id){
        $user = $GLOBALS['auth_user'] = AuthMiddleware::handle();

        $data = $this->todo->getASingleTodo($id, $user->id);
        
        if(!$data){
             Response::json(['error' => 'Todo not found'], 404);
            return;
        }

        Response::json($data, 200);

    }

    public function store(){

        $user = $GLOBALS['auth_user'] = AuthMiddleware::handle();

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
        $result = $this->todo->createTodo($data, $user->id);

        Response::json($result, 201);
    }

    public function update($id)
    {
        $user = $GLOBALS['auth_user'] = AuthMiddleware::handle();

        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->todo->update($id,$input,$user->id);
        Response::json($result, 200);
    }

    public function destroy($id)
    {
        $result = $this->todo->delete($id);
        Response::json($result, 200);
    }
}