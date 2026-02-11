<?php
require_once __DIR__ . '/../Core/Database.php';
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findUserByEmail($data)
    {
       $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
       
       $stmt->execute([$data]);
       return $stmt->fetch(PDO::FETCH_ASSOC);
    }


   public function createUser($name, $email, $hashedPassword)
   {
       $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
       
       return $stmt->execute([$name, $email, $hashedPassword]);
   }

}