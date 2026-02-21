<?php
// app/models/Usuario.php
class Usuario {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM usuarios WHERE deleted_at IS NULL");
        return $stmt->fetchAll();
    }
}