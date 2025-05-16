<?php
require_once  './config/database.php';
require_once './controllers/RecipeController.php';

// Підключення до бази даних
$database = new Database();
$db = $database->getConnection();

// Ініціалізація контролера
$recipeController = new RecipeController($db);

// Отримати метод запиту та URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Основний шлях API
$basePath = '/api/index.php/recipes';

// Видаляємо базовий шлях з URI
$endpoint = str_replace($basePath, '', $requestUri);
$endpoint = str_replace('/index.php' , '',$endpoint);
$endpoint = trim($endpoint, '/');
$parts = explode('/', $endpoint);

$param1 = $parts[0] ?? null;
$param2 = $parts[1] ?? null;

// Обробка запитів
switch ($requestMethod) {
    case 'GET':
        if (empty($param1)) {
            $recipeController->getAll();
        } elseif (is_numeric($param1)) {
            $recipeController->getOne($param1);
        } elseif ($param1 === 'category' && is_numeric($param2)) {
            $recipeController->getByCategory($param2);
        } elseif ($param1 === 'user' && is_numeric($param2)) {
            $recipeController->getByUser($param2);
        } else {
            $recipeController->sendResponse(404, ['error' => 'Не знайдено']);
        }
        break;

    case 'POST':
        if (empty($param1)) {
            $recipeController->create();
        } else {
            $recipeController->sendResponse(404, ['error' => 'Не знайдено']);
        }
        break;

    case 'PUT':
        if (is_numeric($param1)) {
            $recipeController->update($param1);
        } else {
            $recipeController->sendResponse(404, ['error' => 'Не знайдено']);
        }
        break;

    case 'DELETE':
        if (is_numeric($param1)) {
            $recipeController->delete($param1);
        } else {
            $recipeController->sendResponse(404, ['error' => 'Не знайдено']);
        }
        break;

    default:
        $recipeController->sendResponse(405, ['error' => 'Метод не підтримується']);
        break;
}
?>
