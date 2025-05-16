<?php
class Recipe {
    private $db;
    private $table = 'recipes';

    public function __construct($db) {
        $this->db = $db;
    }

    // Отримати всі рецепти
    public function getAll() {
        $result = $this->db->query("SELECT * FROM $this->table");
        return $result->fetch_all(MYSQLI_ASSOC);
    }



    // Отримати рецепт по ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Створити новий рецепт
    public function create($data) {
        // Обов'язкові поля
        if (empty($data['title']) || empty($data['category_id'])) {
            error_log("Відсутні обов'язкові поля: title або category_id");
            return false;
        }

        // Підготовка даних з значеннями за замовчуванням
        $title = $data['title'];
        $description = $data['description'] ?? '';
        $instructions = $data['instructions'] ?? '';
        $image = $data['image'] ?? 'assets/images/none_image.jpg';
        $category_id = (int)$data['category_id'];
        $user_id = (int)($data['user_id'] ?? 0);
        $active = (int)($data['active'] ?? 1);

        // Підготовка запиту
        $query = "INSERT INTO $this->table 
             (title, description, instructions, image, category_id, user_id, active) 
             VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Помилка підготовки запиту: " . $this->db->error);
            return false;
        }

        // Прив'язка параметрів - важливо передавати змінні, а не значення напряму
        $bound = $stmt->bind_param(
            'ssssiii',  // 4 string, 3 integer
            $title,
            $description,
            $instructions,
            $image,
            $category_id,
            $user_id,
            $active
        );

        if (!$bound) {
            error_log("Помилка прив'язки параметрів: " . $stmt->error);
            return false;
        }

        if (!$stmt->execute()) {
            error_log("Помилка виконання запиту: " . $stmt->error);
            return false;
        }

        return $this->db->insert_id;
    }

    // Оновити рецепт
    public function update($id, $data) {
        // Поля, які можна оновлювати
        $allowedFields = [
            'title' => 's',
            'description' => 's',
            'instructions' => 's',
            'image' => 's',
            'category_id' => 'i',
            'user_id' => 'i',
            'active' => 'i'
        ];

        // Фільтруємо тільки дозволені поля
        $updateData = array_intersect_key($data, $allowedFields);

        // Якщо немає полів для оновлення
        if (empty($updateData)) {
            return false;
        }

        // Готуємо частини SQL-запиту
        $setParts = [];
        $types = '';
        $values = [];

        foreach ($updateData as $field => $value) {
            $setParts[] = "$field = ?";
            $types .= $allowedFields[$field];
            $values[] = $value;
        }

        // Додаємо ID в кінець для умови WHERE
        $values[] = $id;
        $types .= 'i'; // тип для ID

        // Будуємо SQL-запит
        $query = "UPDATE $this->table SET " . implode(', ', $setParts) .
            ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Помилка підготовки запиту: " . $this->db->error);
            return false;
        }

        // Прив'язка параметрів з динамічними типами
        $stmt->bind_param($types, ...$values);

        if (!$stmt->execute()) {
            error_log("Помилка виконання запиту: " . $stmt->error);
            return false;
        }

        return $stmt->affected_rows > 0;
    }
    // Видалити рецепт
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Отримати рецепти по категорії
    public function getByCategory($categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE category_id = ?");
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Отримати рецепти по користувачу
    public function getByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Збільшити лічильник переглядів
    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE $this->table SET views = views + 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
?>
