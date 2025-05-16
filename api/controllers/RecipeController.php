<?php
require_once './models/Recipe.php';

class RecipeController {
    private $recipeModel;

    public function __construct($db) {
        $this->recipeModel = new Recipe($db);
    }

    // Отримати всі рецепти
    public function getAll() {
        $recipes = $this->recipeModel->getAll();
        $this->sendResponse(200, $recipes);
    }

    // Отримати один рецепт
    public function getOne($id) {
        $recipe = $this->recipeModel->getById($id);

        if ($recipe) {
            $this->recipeModel->incrementViews($id);
            $this->sendResponse(200, $recipe);
        } else {
            $this->sendResponse(404, ['error' => 'Рецепт не знайдено']);
        }
    }

    // Створити рецепт
    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['title']) || empty($data['description'])) {
            $this->sendResponse(400, ['error' => 'Назва та опис обов\'язкові']);
            return;
        }

        $id = $this->recipeModel->create($data);
        $this->sendResponse(201, ['id' => $id]);
    }

    // Оновити рецепт
    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->recipeModel->update($id, $data);

        if ($success) {
            $this->sendResponse(200, ['message' => 'Рецепт оновлено']);
        } else {
            $this->sendResponse(404, ['error' => 'Рецепт не знайдено']);
        }
    }

    // Видалити рецепт
    public function delete($id) {
        $success = $this->recipeModel->delete($id);

        if ($success) {
            $this->sendResponse(200, ['message' => 'Рецепт видалено']);
        } else {
            $this->sendResponse(404, ['error' => 'Рецепт не знайдено']);
        }
    }

    // Отримати рецепти по категорії
    public function getByCategory($categoryId) {
        $recipes = $this->recipeModel->getByCategory($categoryId);
        $this->sendResponse(200, $recipes);
    }

    // Отримати рецепти по користувачу
    public function getByUser($userId) {
        $recipes = $this->recipeModel->getByUser($userId);
        $this->sendResponse(200, $recipes);
    }

    // Відправити відповідь
    public function sendResponse($statusCode, $data) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
?>
