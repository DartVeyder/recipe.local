<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Recipe.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Ingredient.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Wishlist.php';

class ApiRecipeController
{
    private $recipe;
    private $ingredient;
    private $category;
    private $wishlist;

    public function __construct()
    {
        $this->recipe = new Recipe();
        $this->ingredient = new Ingredient();
        $this->category = new Category();
        $this->wishlist = new Wishlist();
    }

    public function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function index()
    {
        $searchQuery = $_GET['search'] ?? '';
        $sorts = $this->getSort();
        $category_ids = $_GET['categories'] ?? [];
        $ingredientGroup_ids = $_GET['ingredientGroups'] ?? [];

        $recipes = $this->recipe->all($searchQuery, $sorts['sort'], $category_ids, $ingredientGroup_ids);
        $categories = $this->category->all();
        $ingredientGroups = $this->ingredient->allGroups();

        $user_id = $_GET['user_id'] ?? null;
        $countWishlist = $user_id ? $this->wishlist->countByUserId($user_id) : 0;

        $this->jsonResponse([
            'recipes' => $recipes,
            'sorts' => $sorts['sorts'],
            'currentSort' => $sorts['sort'],
            'categories' => $categories,
            'ingredientGroups' => $ingredientGroups,
            'countWishlist' => $countWishlist
        ]);
    }

    public function show($id)
    {
        $this->recipe->incrementViews($id);
        $recipe = $this->recipe->find($id);
        $ingredients = $this->ingredient->allToRecipe($id);
        $user_id = $_GET['user_id'] ?? null;
        $countWishlist = $user_id ? $this->wishlist->countByUserId($user_id) : 0;

        $this->jsonResponse([
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'countWishlist' => $countWishlist
        ]);
    }

    public function store()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $errors = [];

        if (empty($input['title'])) $errors['title'] = 'Назва обов\'язкова';
        if (empty($input['description'])) $errors['description'] = 'Опис обов\'язковий';
        if (empty($input['instructions'])) $errors['instructions'] = 'Інструкція обов\'язкова';
        if (empty($input['category_id'])) $errors['category_id'] = 'Категорія обов\'язкова';
        if (empty($input['ingredients'])) $errors['ingredients'] = 'Додайте хоча б один інгредієнт';

        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
        }

        $data = [
            'title' => $input['title'],
            'description' => $input['description'],
            'category_id' => $input['category_id'],
            'instructions' => $input['instructions'],
            'image' => $input['image'] ?? null,
            'user_id' => $input['user_id']
        ];

        $recipe_id = $this->recipe->create($data);

        foreach ($input['ingredients'] as $ingredient) {
            if (!empty($ingredient['quantity'])) {
                $this->ingredient->addIngredientToRecipe($recipe_id, $ingredient['id'], $ingredient['quantity']);
            }
        }

        $this->jsonResponse(['message' => 'Рецепт створено', 'id' => $recipe_id], 201);
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $errors = [];

        if (empty($input['title'])) $errors['title'] = 'Назва обов’язкова';
        if (empty($input['description'])) $errors['description'] = 'Опис обов’язковий';
        if (empty($input['instructions'])) $errors['instructions'] = 'Інструкція обов’язкова';
        if (empty($input['category_id'])) $errors['category_id'] = 'Категорія обов’язкова';

        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
        }

        $data = [
            'title' => $input['title'],
            'description' => $input['description'],
            'category_id' => $input['category_id'],
            'instructions' => $input['instructions'],
            'image' => $input['image'] ?? null,
            'user_id' => $input['user_id']
        ];

        if ($this->recipe->update($id, $data)) {
            $this->jsonResponse(['message' => 'Рецепт оновлено']);
        } else {
            $this->jsonResponse(['message' => 'Помилка при оновленні рецепта'], 500);
        }
    }

    public function delete($id)
    {
        if ($this->recipe->delete($id)) {
            $this->jsonResponse(['message' => 'Рецепт видалено']);
        } else {
            $this->jsonResponse(['message' => 'Помилка при видаленні рецепта'], 500);
        }
    }

    private function getSort()
    {
        $sorts = [
            'title_desc' => ['name' => 'За назвою по спаданню', 'sql' => 'title DESC'],
            'title_asc' => ['name' => 'За назвою по зростанню', 'sql' => 'title ASC'],
            'date_desc' => ['name' => 'За датою по спаданню', 'sql' => 'created_at DESC'],
            'date_asc' => ['name' => 'За датою по зростанню', 'sql' => 'created_at ASC'],
            'views_asc' => ['name' => 'За популярністю по спаданню', 'sql' => 'views ASC'],
            'views_desc' => ['name' => 'За популярністю по зростанню', 'sql' => 'views DESC'],
        ];
        return [
            'sort' => $_GET['sort'] ?? 'date_asc',
            'sorts' => $sorts
        ];
    }
}
