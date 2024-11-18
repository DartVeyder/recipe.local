<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Wishlist.php';

class WishlistController  extends  Controller
{
    private $user;

    public function __construct()
    {
        $this->wishlist = new Wishlist();
    }

    public function index()
    {
        $recipes=  $this->wishlist->allRecipesByUserId($_SESSION['user_id']);
        if ( isset($_SESSION['user_id'])) {
            $countWishlist = $this->wishlist->countByUserId($_SESSION['user_id']);
        }else{
            $countWishlist = 0;
        }

        $this->view('wishlist/index', ['recipes'=>$recipes, 'countWishlist' => $countWishlist]);
    }

    public function create()
    {
        $this->wishlist->create($_GET['user_id'], $_GET['recipe_id']);
        header("Location: /");
    }

    public function delete($id)
    {
        // Викликаємо метод deleteRecipe з моделі
        if ($this->wishlist->delete($id)) {

            header("Location:    ".$_SERVER['HTTP_REFERER'] );

            exit();
        } else {
            // Якщо не вдалося, показуємо повідомлення
            echo "Помилка при видаленні!";
        }
    }
}
