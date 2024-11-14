<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        $query = $this->db->query("SELECT * FROM categories");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
