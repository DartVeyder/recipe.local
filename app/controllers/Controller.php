<?php

class Controller
{
    // Функція для підключення view
    public function view($view, $data = [])
    {
        // Підключаємо загальний шаблон
        include_once $_SERVER['DOCUMENT_ROOT'] . '/app/views/layout.php';

        // Тепер підключаємо конкретне представлення
        $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            // Перевіряємо, чи є дані для шаблону
            if (!empty($data)) {
                extract($data);  // Перетворює масив в окремі змінні
            }
            include_once $viewPath;
        } else {
            echo "View not found!";
        }
    }

}
