<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

class Wishlist
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($user_id, $recipe_id)
    {
        if(empty($user_id)){
            return  false;
        }

        if(empty($recipe_id)){
            return  false;
        }

        // Підготовка SQL запиту для вставки даних
        $query = "INSERT IGNORE  INTO wishlist (user_id, recipe_id)
                  VALUES (:user_id, :recipe_id)";

        // Підготовка та виконання запиту
        $stmt = $this->db->prepare($query);

        // Прив'язка значень
        $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        // Виконання запиту
        if ($stmt->execute()) {
            return true; // Успішне додавання
        } else {
            return false; // Помилка
        }
    }

    public function countByUserId($userId)
    {
        $query = "SELECT COUNT(*) AS total_records FROM wishlist WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_records'];
    }
    public function allRecipesByUserId($userId)
    {
        $query = "
            SELECT w.id as wishlist_id, w.recipe_id, r.*, c.name as category_name, u.name as user_name
            FROM wishlist w
            JOIN recipes r ON w.recipe_id = r.id
            LEFT JOIN categories c ON r.category_id = c.id
            LEFT JOIN users u ON r.user_id = u.id
            WHERE w.user_id = :user_id
";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        // Перевірка наявності ID
        if (empty($id)) {
            return false;
        }

        // SQL запит для видалення рецепта за ID
        $query = $this->db->prepare("DELETE FROM wishlist WHERE id = :id");

        // Виконання запиту
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        return $query->execute();
    }
}
