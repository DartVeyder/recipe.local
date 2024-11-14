<?php
session_start();
// Визначення абсолютних шляхів для підключення файлів
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/App.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/Database.php';

// Запуск основного додатка
$app = new App();
