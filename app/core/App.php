<?php

class App
{
    protected $controller = 'RecipeController'; // Контролер за замовчуванням
    protected $method = 'index'; // Метод за замовчуванням
    protected $params = []; // Параметри

    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/routes.php'; // Абсолютний шлях до маршруту

        $url = $this->parseUrl();

        $routePath = isset($url[0]) ? $url[0] . '/' . ($url[1] ?? 'index') : '';

        // Пошук маршруту в маршрутах
        $route = route($routePath, $routes);

        if ($route) {
            $this->controller = $route['controller'];
            $this->method = $route['method'];
            unset($url[0], $url[1]);
        }

        // Абсолютний шлях до контролера
        require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Решта частин URL розглядаються як параметри
        $this->params = $url ? array_values($url) : [];

        // Виклик контролера, методу та передачі параметрів
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Метод для парсингу URL
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
