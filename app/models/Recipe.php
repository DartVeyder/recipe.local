<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

class Recipe
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Метод для формування сортування
    private function getOrderBy($sort)
    {
        switch ($sort) {
            case 'title_desc':
                return 'title DESC';
            case 'date_asc':
                return 'created_at ASC';
            case 'date_desc':
                return 'created_at DESC';
            case 'views_desc':
                return 'views DESC';
            case 'views_asc':
                return 'views ASC';
            default:
                return 'title ASC'; // Значення за замовчуванням
        }
    }

    // Метод для побудови фільтрації за категоріями
    private function buildCategoryFilter(&$sql, &$params, $category_ids)
    {
        if (!empty($category_ids)) {
            $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
            $sql .= " AND r.category_id IN ($placeholders)";
            $params = array_merge($params, $category_ids);
        }
    }

    private function buildingredientGroupFilter(&$sql, &$params, $ingredientGroup_ids)
    {
        if (!empty($ingredientGroup_ids)) {
            // Додаємо умову для фільтрації по групам інгредієнтів
            $sql .= " AND EXISTS (
                    SELECT 1 
                    FROM recipe_ingredient ri
                    JOIN ingredients i ON ri.ingredient_id = i.id
                    WHERE ri.recipe_id = r.id
                    AND i.group_id IN (" . implode(',', array_fill(0, count($ingredientGroup_ids), '?')) . ")
                 )";

            // Додаємо параметри для фільтрації за групами інгредієнтів
            $params = array_merge($params, $ingredientGroup_ids);
        }
    }


    // Основний метод для отримання рецептів з урахуванням фільтрів
    public function all($search = '', $sort = 'date_asc', $category_ids = [], $ingredientGroup_ids = [])
    {
        $orderBy = $this->getOrderBy($sort);

        // Базовий SQL-запит
        $sql = "SELECT r.*, c.name as category_name, COUNT(rv.id) AS total_reviews, u.name as user_name, u.id as user_id , CASE 
                WHEN w.recipe_id IS NOT NULL THEN w.id 
                ELSE 0 
            END AS is_in_wishlist
            FROM recipes r
            LEFT JOIN categories c ON r.category_id = c.id
            LEFT JOIN reviews rv ON rv.recipe_id = r.id
            LEFT JOIN users u ON r.user_id = u.id 
            LEFT JOIN wishlist w ON r.id = w.recipe_id AND w.user_id = ?
            WHERE r.title LIKE ?";

        $params[] = $_SESSION['user_id'] ?? null;
        // Параметри для пошукового запиту
        $params[] = "%$search%";

        // Додаємо фільтр за категоріями
        $this->buildCategoryFilter($sql, $params, $category_ids);

        // Додаємо фільтр за групами інгредієнтів
        $this->buildingredientGroupFilter($sql, $params, $ingredientGroup_ids);

        // Додаємо групування і сортування
        $sql .= " GROUP BY r.id ORDER BY $orderBy";

        // Виконуємо запит
        $query = $this->db->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }




    public function find($id)
    {
        $query = $this->db->prepare("SELECT r.id,r.category_id, r.title, r.image, r.description, r.created_at, r.views, c.name as category_name, COUNT(rv.id) AS total_reviews , r.instructions as instructions FROM recipes r 
                LEFT JOIN categories c ON  r.category_id = c.id 
                LEFT JOIN reviews rv ON  rv.recipe_id  = r.id  
                WHERE  r.id = :id GROUP BY r.id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Підготовка SQL запиту для вставки даних
        $query = "INSERT INTO recipes (title, description, instructions, image, category_id, user_id)
                  VALUES (:title, :description, :instructions, :image, :category_id, :user_id)";

        // Підготовка та виконання запиту
        $stmt = $this->db->prepare($query);

        // Прив'язка значень
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':instructions', $data['instructions'], PDO::PARAM_STR);
        $stmt->bindParam(':image', $data['image'], PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);

        // Виконання запиту
        if ($stmt->execute()) {
            return $this->db->lastInsertId();; // Успішне додавання
        } else {
            return false; // Помилка
        }
    }

    public function update($id, $data)
    {

        // Підготовка SQL запиту для оновлення даних
        $query = "UPDATE recipes 
              SET title = :title, 
                  description = :description, 
                  instructions = :instructions, 
                  image = :image, 
                  category_id = :category_id, 
                  user_id = :user_id 
              WHERE id = :id";

        // Підготовка та виконання запиту
        $stmt = $this->db->prepare($query);

        // Прив'язка значень
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':instructions', $data['instructions'], PDO::PARAM_STR);
        $stmt->bindParam(':image', $data['image'], PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Виконання запиту
        if ($stmt->execute()) {
            return true; // Успішне оновлення
        } else {
            return false; // Помилка
        }
    }

    // Метод для видалення рецепта
    public function delete($id)
    {
        // Перевірка наявності ID
        if (empty($id)) {
            return false;
        }

        // SQL запит для видалення рецепта за ID
        $query = $this->db->prepare("DELETE FROM recipes WHERE id = :id");

        // Виконання запиту
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        return $query->execute();
    }

    public function incrementViews($id)
    {
        // SQL запит для збільшення кількості переглядів
        $query = "UPDATE recipes SET views = views + 1 WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();  // Повертає true, якщо успішно
    }


}
