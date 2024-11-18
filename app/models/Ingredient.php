<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

class Ingredient
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public  function all(){
        $query = $this->db->query("SELECT * FROM ingredients");
        return $query->fetchAll(PDO::FETCH_ASSOC);
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

    public function allGroups()
    {
        $query = $this->db->query("SELECT * FROM ingredient_groups");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addIngredientToRecipe($recipe_id, $ingredient_id, $quantity)
    {
        $sql = "INSERT INTO recipe_ingredient (recipe_id, ingredient_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$recipe_id, $ingredient_id, $quantity]);
    }

}
