<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/header.php'); ?>
<?php if (isset($_SESSION['user_id'])): ?>
    <a class="create-recipe" href="?url=recipes/create"><button type="button" class="btn btn-primary">Додати рецепт</button></a>
<?php endif; ?>
<div class="container-fluid mt-3 d-flex flex-row-reverse">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/login-or-logout-button.php'); ?>
</div>
<div class="container mt-5">

    <div class="row">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/search.php'); ?>

        <div class="col-2">

            <div class="btn-group">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                    <?php echo $sorts[$getSort]['name']; ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="defaultDropdown">

                    <?php foreach ($sorts as $key => $sort): ?>
                         <?php if ( $key !=  $getSort): ?>
                            <li>
                                <a class="dropdown-item btn btn-primary" href="?<?php echo http_build_query(array_merge($_GET, ['sort' => $key])); ?>">
                                    <?php echo $sort['name']; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">


            <?php if (isset($_GET['search'])): ?>
                <li class="breadcrumb-item active" aria-current="page"><a href="/">Головна</a></li>
                <li class="breadcrumb-item active" aria-current="page"> <?php echo $_GET['search'] ?></li>
            <?php else:  ?>
                <li class="breadcrumb-item active" aria-current="page">Головна</li>
            <?php endif; ?>

        </ol>
    </nav>
    <div class="row">

        <div class="col-2">
            <p><b>Фільтр</b></p>

            <?php
            // Зберігаємо параметр `sort`, якщо він є, і видаляємо інші параметри
                $clearFilterUrl = '?url=recipes/index';
                if (isset($_GET['sort'])) {
                    $clearFilterUrl .= '&sort=' . htmlspecialchars($_GET['sort']);
                }
            ?>

            <?php if (isset($_GET['search']) || isset($_GET['categories'])): ?>
                <!-- Кнопка очистки тільки фільтрів -->
                <a href="<?php echo $clearFilterUrl; ?>" class="btn  ms-2 mb-1"><i class="fas fa-times"></i> Очистити </a>

            <?php endif; ?>

            <form method="GET" action="?url=recipes/index">
            <div class="ms-3">
                <label><b>Категорії:</b></label>
                <?php foreach ($categories as $category): ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="categories[]" id="category_<?php echo $category['id']; ?>" value="<?php echo $category['id']; ?>"
                            <?php echo (isset($_GET['categories']) && in_array($category['id'], $_GET['categories'])) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="category_<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </label>
                    </div>

                <?php endforeach; ?>

            </div>
            <br>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Застосувати</button>
            </div>
            </form>
        </div>
        <div class="col-10">
            <div class="row">
            <?php if (  !$recipes) : ?>
                <div class="col-12 col-sm-8 col-md-6 col-lg-4 mb-4">
                    <p>За даним пошуком відсутні рецепти</p>
                </div>
            <?php endif; ?>
            <?php foreach ($recipes as $recipe): ?>
                <?php
                $created_at = new DateTime($recipe['created_at']);
                ?>

                <div class="col-12 col-sm-8 col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <img class="card-img-top" style="height: 16rem;" src="<?php echo ($recipe['image'] ? $recipe['image'] : 'assets/images/none_image.jpg'); ?>" alt="<?php echo $recipe['title']; ?>">

                        <div class="card-img-overlay" style="right: auto;bottom: auto;width: 100%;display: flex;align-items: center;justify-content: space-between;">
                            <a href="?categories[]=<?php echo $recipe['category_id']; ?>" class="btn btn-light btn-sm"><?php echo $recipe['category_name']; ?></a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="action">
                                <a href="?url=recipes/edit/<?php echo $recipe['id']; ?>" class="fas fa-edit text-info"></a>
                                <a href="?url=recipes/delete/<?php echo $recipe['id']; ?>" class="fas fa-trash-alt text-danger ml-3"></a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $recipe['title']; ?></h4>
                            <p class="card-text"><?php echo $recipe['description']; ?></p>
                            <a href=" ?url=recipes/show/<?php echo $recipe['id']; ?>" class="btn btn-info">Читати</a>
                        </div>
                        <div class="card-footer text-muted d-flex justify-content-between bg-transparent border-top-0">
                            <div class="views">
                                <?php echo $created_at->format('d.m.Y, H:i'); ?>
                            </div>
                            <div class="stats">
                                <i class="far fa-eye"></i> <?php echo $recipe['views']; ?>
                                <i class="far fa-comment"></i> <?php echo $recipe['total_reviews']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
