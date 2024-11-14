<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/header.php'); ?>
<div class="container-fluid mt-3 d-flex flex-row-reverse">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/login-or-logout-button.php'); ?>
</div>

<div class="container">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Добавити рецепт</li>
            </ol>
        </nav>
    </div>
    <div class="form mx-auto d-block w-100">
        <div class="page-header text-center">
            <h1>Добавити рецепт</h1>
        </div>

        <form id="recipe-form" action="?url=recipes/store" method="post" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group mt-3">
                    <label for="title">Назва</label>
                    <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    <?php if (!empty($errors['title'])): ?>
                        <p class="text-danger"><?php echo $errors['title']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="description">Опис</label>
                    <textarea class="form-control" name="description" id="description" rows="3"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                        <p class="text-danger"><?php echo $errors['description']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="instructions">Інструкція</label>
                    <textarea class="form-control" name="instructions" id="instructions" rows="3"><?php echo htmlspecialchars($_POST['instructions'] ?? ''); ?></textarea>
                    <?php if (!empty($errors['instructions'])): ?>
                        <p class="text-danger"><?php echo $errors['instructions']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="image">Посилання на фото</label>
                    <input type="text" class="form-control" name="image" id="image" value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>">
                </div>

                <div class="form-group mt-3">
                    <label for="category">Категорія</label>
                    <select class="form-select" id="category" name="category_id">
                        <option value="">Виберіть категорію</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['category_id'])): ?>
                        <p class="text-danger"><?php echo $errors['category_id']; ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-3">
                    <input hidden type="text" class="form-control" name="user_id" value="<?php echo  $_SESSION['user_id']?>"  >
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="submit" class="btn btn-primary">Зберегти</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
