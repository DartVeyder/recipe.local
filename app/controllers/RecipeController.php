<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Recipe.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Ingredient.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/Controller.php';
class RecipeController extends  Controller
{
    private $recipe;
    private $ingredient;

    public function __construct()
    {
        $this->recipe = new Recipe();
        $this->ingredient = new Ingredient();
        $this->category = new Category();
    }

    public function index()
    {
        $searchQuery = $_GET['search'] ?? '';
        $getSorts = $this->getSort();
        $category_ids = $_GET['categories'] ?? [];
        $recipes = $this->recipe->all( $searchQuery, $getSorts['sort'], $category_ids);
        $categories = $this->category->all();

        // Підключаємо представлення та передаємо дані
        $this->view('recipes/index', [
            'recipes' => $recipes,
            'sorts' =>$getSorts['sorts'],
            'getSort' =>  $getSorts['sort'],
            'categories' => $categories]);
    }

    // Метод для відображення форми створення рецепта
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        $categories = $this->category->all();
        $this->view('recipes/create' , ['categories' => $categories]);
    }

    public function store()
    {
        $errors = [];

        // Перевірка обов’язкових полів
        if (empty($_POST['title'])) {
            $errors['title'] = 'Назва обов’язкова';
        }

        if (empty($_POST['description'])) {
            $errors['description'] = 'Опис обов’язковий';
        }

        if (empty($_POST['instructions'])) {
            $errors['instructions'] = 'Інструкція обов’язкова';
        }

        if (empty($_POST['category_id']) || $_POST['category_id'] === 'Виберіть категорію') {
            $errors['category_id'] = 'Категорія обов’язкова';
        }

        if (!empty($errors)) {
            // Повертаємо користувача на форму з помилками
            $categories = $this->category->all();
            $this->view('recipes/create',['errors' => $errors,'categories' => $categories] );
            return;
        }

        // Якщо немає помилок, зберігаємо рецепт
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'instructions' => $_POST['instructions'],
            'image' => $_POST['image'],
            'user_id' => $_POST['user_id']
        ];

        $this->recipe->create($data);
        header('Location: ?url=recipes/index');
        exit();
    }

    private function getSort()
    {
        $sorts = [
            'title_desc' => [
                'name' =>  'За назвою по спаданню',
                'sql' => 'title DESC'
            ],
            'title_asc' => [
                'name' => 'За назвою по зростанню',
                'sql' => 'title ASC'
            ],

            'date_desc' => [
                'name' => 'За датою по спаданню',
                'sql' => 'created_at DESC'
            ],
            'date_asc' => [
                'name' => 'За датою по зростанню',
                'sql' => 'created_at ASC'
            ],
        ];


        return [
            'sort' =>$_GET['sort'] ?? 'date_asc',
            'sorts' => $sorts
        ] ;

    }

    // Метод для відображення окремого рецепта
    public function show($id)
    {
        // Отримуємо рецепт за ID
        $recipe = $this->recipe->find($id);
        $ingredients = $this->ingredient->allToRecipe($id);

        // Підключаємо представлення та передаємо дані
        $this->view('recipes/show', ['recipe' => $recipe, 'ingredients'=>$ingredients]);
    }
    public function delete($id)
    {
        // Викликаємо метод deleteRecipe з моделі
        if ($this->recipe->delete($id)) {
            // Якщо успішно, перенаправляємо назад на список
            header("Location: /");
            exit();
        } else {
            // Якщо не вдалося, показуємо повідомлення
            echo "Помилка при видаленні рецепта!";
        }
    }

}
