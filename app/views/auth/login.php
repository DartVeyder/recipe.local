<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/header.php'); ?>
<div class="container">
    <div class="form mx-auto d-block w-100">
        <div class="page-header text-center">
            <h1>Авторизація</h1>
        </div>

        <form id="member-registration" action="?url=auth/login" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group mt-3">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <p style="color:red;"><?php echo $errors['email']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group mt-3">
                    <label for="exampleInputPassword1">Пароль</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                    <?php if (!empty($errors['password'])): ?>
                        <p style="color:red;"><?php echo $errors['password']; ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($errors['general'])): ?>
                    <p style="color:red; text-align: center;"><?php echo $errors['general']; ?></p>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="form-group d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary">Увійти</button>
                    </div>
                    <div class="form-check form-group d-flex justify-content-end">
                        <a href="?url=auth/register">Зареєструватися</a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
