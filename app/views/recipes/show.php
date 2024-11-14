<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/header.php'); ?>
<div class="container-fluid mt-3 d-flex flex-row-reverse">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/login-or-logout-button.php'); ?>
</div>
<div class="container mt-5">
    <div class="row">
        <div class="col-9">
            <form method="GET" action="?url=recipes/index">
                <div class="input-group mb-3 d-flex align-items-center">
                    <input type="text" class="form-control" name="search" placeholder="Пошук" aria-label="Пошук" aria-describedby="basic-addon2" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Пошук</button>
                    </div>

                    <?php if (isset($_GET['search'])): ?>
                        <a href="?url=recipes/index" class="btn-close ms-2"></a>
                    <?php endif; ?>

                </div>
            </form>
        </div>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Головна</a></li>
            <li class="breadcrumb-item active" aria-current="page"> <?php echo $data['recipe']['title'] ?></li>
        </ol>
    </nav>

    <div class="recipe-card">
        <!-- Заголовок зображення -->
        <div class="recipe-header" style=" background: url('<?php echo  $data['recipe']['image'] ?>') center/cover no-repeat;">
            <h1>Рецепт:  <?php echo $data['recipe']['title'] ?></h1>
        </div>

        <!-- Інгредієнти та інструкції -->
        <div class="row g-0">
            <div class="col-md-6 ingredients">
                <h3>Інгредієнти</h3>
                <ul class="list-group list-group-flush">
                    <?php

                    foreach( $data['ingredients'] as $ingredient ){
                        echo  "<li class=list-group-item>$ingredient[name]: $ingredient[quantity]</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-6 instructions">
                <h3>Інструкції</h3>
                <p>
                    <?php  echo  $data['recipe']['instructions']; ?>
                </p>
            </div>
        </div>

        <!-- Кнопки дій -->
        <div class="row g-0">
            <div class="col-12 text-center py-4">
                <a href="#" class="btn btn-custom me-2">Редагувати рецепт</a>
                <a href="#" class="btn btn-danger">Видалити рецепт</a>
            </div>
        </div>
    </div>
</div>
<style>

    .recipe-card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
    }
    .recipe-header {
        height: 300px;
        position: relative;
    }
    .recipe-header h1 {
        color: #fff;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        position: absolute;
        bottom: 20px;
        left: 20px;
    }
    .ingredients, .instructions {
        padding: 20px;
    }
    .list-group-item {
        background-color: rgba(248, 249, 250, 0.9);
        border: none;
    }
    .btn-custom {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    .btn-custom:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
</style>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
