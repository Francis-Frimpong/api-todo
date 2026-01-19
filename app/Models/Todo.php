<?php
require_once __DIR__ . '/../Core/Database.php';
class Todo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all()
    {
        return $this->db->query('SELECT * FROM todos')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $title = trim($data['title']);
        $description = trim($data['description']);

        $stmt = $this->db->prepare('INSERT INTO todos (title, description) VALUES (?, ?)');
        $stmt->execute([$title, $description]);

        return ['message' => 'Todo created'];  
    }

    public function getTodoByID($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM todos WHERE id = ?');
        $stmt->execute([$id]);
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