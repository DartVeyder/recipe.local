<?php

$routes = [
    // Маршрути для рецептів
    'recipes/index' => ['controller' => 'RecipeController', 'method' => 'index'],
    'recipes/show' => ['controller' => 'RecipeController', 'method' => 'show'],
    'recipes/delete' => ['controller' => 'RecipeController', 'method' => 'delete'],
    'recipes/create' => ['controller' => 'RecipeController', 'method' => 'create'],
    'recipes/store' => ['controller' => 'RecipeController', 'method' => 'store'],
    'recipes/edit' => ['controller' => 'RecipeController', 'method' => 'edit'],
    'recipes/update' => ['controller' => 'RecipeController', 'method' => 'update'],

    'wishlist/create' => ['controller' => 'WishlistController', 'method' => 'create'],
    'wishlist/index' => ['controller' => 'WishlistController', 'method' => 'index'],
    'wishlist/delete' => ['controller' => 'WishlistController', 'method' => 'delete'],

    // Маршрути для авторизації та реєстрації
    'auth/register' => ['controller' => 'AuthController', 'method' => 'register'],
    'auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
    'auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
];

function route($url, $routes)
{
    if (isset($routes[$url])) {
        $controller = $routes[$url]['controller'];
        $method = $routes[$url]['method'];
        return ['controller' => $controller, 'method' => $method];
    }
    return null;
}
