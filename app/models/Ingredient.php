<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

class Ingredient
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function allToRecipe($recipeId)
    {
        // Використовуємо метод prepare замість query
        $query = $this->db->prepare('SELECT ri.quantity, i.name FROM recipe_ingredient ri 
                                 LEFT JOIN ingredients i ON ri.ingredient_id = i.id
                                 WHERE ri.recipe_id = :recipe_id');

        // Прив'язуємо значення параметра :recipe_id
        $query->execute(['recipe_id' => $recipeId]);

        // Повертаємо всі результати запиту у вигляді масиву
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}