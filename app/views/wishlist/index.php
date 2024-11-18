<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/header.php'); ?>

<div class="container-fluid mt-3 d-flex flex-row-reverse">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/login-or-logout-button.php'); ?>
</div>
<h1 class="text-center mb-4">Збережені</h1>
<div class="container">
    <div class="row">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/search.php'); ?>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Головна</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Збережені</li>
        </ol>
    </nav>
    <?php foreach ($recipes as $recipe): ?>
        <?php
        $created_at = new DateTime($recipe['created_at']);
        ?>
    <div class="my-5 border-0">
        <div class="row">
            <div class="col-12 col-md-4 " style="    height: 226px;overflow: hidden">
                <a href=" ?url=recipes/show/<?php echo $recipe['id']; ?>">
                    <img src="<?php echo  $recipe['image'] ; ?>" class="img-fluid" alt="placeholder">
                </a>
            </div>
            <div class="col-12 col-md-8 d-flex flex-column justify-content-center">
                <a href=" ?url=recipes/show/<?php echo $recipe['id']; ?>"><h4>  <?php echo  $recipe['title'] ; ?></h4></a>
                <p class="text-muted small">Категорія :  <?php echo  $recipe['category_name'] ; ?>,  Опубліковано:   <?php echo  $created_at->format('d.m.Y'); ; ?></p>
                <p class="short-desc text-justify">
                    <?php echo  $recipe['description'] ; ?>
                </p>
                <div class="seller">
                        <p class="m-0 p-0 ml-2"> Автор: <?php echo  $recipe['user_name'] ; ?>  </p><br>
                    <a href="?url=wishlist/delete/<?php echo $recipe['wishlist_id']  ?>" class="text-danger ">Видалити</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
