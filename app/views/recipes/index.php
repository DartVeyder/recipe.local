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
                                <a class="dropdown-item btn btn-primary btn-sort" data-sort="&<?php echo http_build_query(array_merge($_GET, ['sort' => $key])); ?>">
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

            <?php if (isset($_GET['search']) || isset($_GET['categories'])|| isset($_GET['ingredientGroups'])): ?>
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
                <div class="ms-3">
                    <label><b>Групи інгридієнтів:</b></label>
                    <?php foreach ($ingredientGroups as $ingredientGroup): ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="ingredientGroups[]" id="ingredientGroup_<?php echo $ingredientGroup['id']; ?>" value="<?php echo $ingredientGroup['id']; ?>"
                                <?php echo (isset($_GET['ingredientGroups']) && in_array($ingredientGroup['id'], $_GET['ingredientGroups'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="ingredientGroup_<?php echo $ingredientGroup['id']; ?>">
                                <?php echo htmlspecialchars($ingredientGroup['name']); ?>
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
        <div class="col-10" id="catalog">
            <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/recipes/list_catalog.php'); ?>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
