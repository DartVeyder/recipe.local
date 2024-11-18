<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Recipe.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Ingredient.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Wishlist.php';
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
        $this->wishlist = new Wishlist();
    }

    public function index()
    {
        $searchQuery = $_GET['search'] ?? '';
        $getSorts = $this->getSort();
        $category_ids = $_GET['categories'] ?? [];
        $ingredientGroup_ids = $_GET['ingredientGroups'] ?? [];
        $recipes = $this->recipe->all( $searchQuery, $getSorts['sort'], $category_ids, $ingredientGroup_ids );
        $categories = $this->category->all();
        $ingredientGroups = $this->ingredient->allGroups();

        if ( isset($_SESSION['user_id'])) {
            $countWishlist = $this->wishlist->countByUserId($_SESSION['user_id']);
        }else{
            $countWishlist = 0;
        }
        // Підключаємо представлення та передаємо дані
        $this->view('recipes/index', [
            'recipes' => $recipes,
            'sorts' =>$getSorts['sorts'],
            'getSort' =>  $getSorts['sort'],
            'categories' => $categories,
            'countWishlist' => $countWishlist,
            'ingredientGroups'=>$ingredientGroups]
            );
    }

    // Метод для відображення форми створення рецепта
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        $categories = $this->category->all();
        $ingredients = $this->ingredient->all();
        $this->view('recipes/create' , ['categories' => $categories, 'ingredients'=>$ingredients]);
    }

    public function edit($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }
        $recipe = $this->recipe->find($id);
        $ingredients = $this->ingredient->all();
        $categories = $this->category->all();
        $this->view('recipes/edit' , ['recipe' => $recipe, 'categories' => $categories, 'ingredients'=>$ingredients]);
    }

    public function store()
    {
        $errors = [];

        // Перевірка обов'язкових полів
        if (empty($_POST['title'])) {
            $errors['title'] = 'Назва обов\'язкова';
        }

        if (empty($_POST['description'])) {
            $errors['description'] = 'Опис обов\'язковий';
        }

        if (empty($_POST['instructions'])) {
            $errors['instructions'] = 'Інструкція обов\'язкова';
        }

        if (empty($_POST['category_id']) || $_POST['category_id'] === 'Виберіть категорію') {
            $errors['category_id'] = 'Категорія обов\'язкова';
        }
        print_r($_POST);
        // Перевірка інгредієнтів
        if (empty($_POST['ingredients'])) {
            $errors['ingredients'] = 'Додайте хоча б один інгредієнт';
        }

        if (!empty($errors)) {
            // Повертаємо користувача на форму з помилками
            $categories = $this->category->all();
            $ingredients = $this->ingredient->all();
            $this->view('recipes/create', ['errors' => $errors, 'categories' => $categories, 'ingredients' => $ingredients]);
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

        // Зберігаємо рецепт і отримуємо його ID
        $recipe_id = $this->recipe->create($data);

        // Додавання інгредієнтів до рецепта
        if (!empty($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $ingredient) {
                if (!empty($ingredient['quantity'])) {
                    $this->ingredient->addIngredientToRecipe($recipe_id, $ingredient['id'], $ingredient['quantity']);
                }
            }
        }

        // Перенаправлення на список рецептів
        header('Location: ?url=recipes/index');
        exit();
    }


    public function update($id)
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
            $recipe = $this->recipe->find($id);
            $categories = $this->category->all();
            $this->view('recipes/edit', ['errors' => $errors, 'recipe' => $recipe, 'categories' => $categories]);
            return;
        }


        // Якщо немає помилок, оновлюємо рецепт
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'instructions' => $_POST['instructions'],
            'image' => $_POST['image'],
            'user_id' => $_POST['user_id']
        ];
        if ($this->recipe->update($id, $data)) {
            header('Location: ?url=recipes/index');
            exit();
        } else {
            echo "Помилка при оновленні рецепту.";
        }
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
            'views_asc' => [
                'name' => 'За популярністю по спаданню',
                'sql' => 'views ASC'
            ],
            'views_desc' => [
                'name' => 'За популярністю по зростанню',
                'sql' => 'views DESC'
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
        // Оновлення кількості переглядів
        $this->recipe->incrementViews($id);

        // Отримуємо рецепт за ID
        $recipe = $this->recipe->find($id);
        $ingredients = $this->ingredient->allToRecipe($id);
        if ( isset($_SESSION['user_id'])) {
            $countWishlist = $this->wishlist->countByUserId($_SESSION['user_id']);
        }else{
            $countWishlist = 0;
        }
        // Підключаємо представлення та передаємо дані
        $this->view('recipes/show', ['recipe' => $recipe, 'ingredients'=>$ingredients,'countWishlist'=>$countWishlist]);
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
