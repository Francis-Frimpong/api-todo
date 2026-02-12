<?php
require_once __DIR__ . '/../Core/Database.php';
class Todo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getTodoByUserID($userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM todos WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // <-- fetchAll returns array
    }

    public function createTodo($data, $userId)
    {
        $title = trim($data['title']);
        $description = trim($data['description']);

        $stmt = $this->db->prepare('INSERT INTO todos (title, description, user_id) VALUES (?, ?, ?)' );
        $stmt->execute([$title, $description, $userId]);

        return ['message' => 'Todo created'];  
    }

    public function getASingleTodo($id, $userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM todos WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id,$data)
    {
        $stmt = $this->db->prepare('UPDATE todos SET title=?,description=? WHERE id =?');
        $stmt->execute([
            $data['title'],
            $data['description'],
            $id
        ]);
        return ['message' => 'Todo updated'];

    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM todos WHERE id=?');
        $stmt->execute([$id]);
        return ['message' => 'Todo deleted'];
    }


}