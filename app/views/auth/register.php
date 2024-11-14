<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/header.php'); ?>
<div class="container ">
    <div class="form mx-auto d-block w-100 ">
        <div class="page-header text-center">
            <h1>Реєстрація</h1>
        </div>

        <form id="member-registration" action="?url=auth/register" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group mt-3">
                    <label for="name">Імя *</label>
                    <input type="text" name="name" class="form-control" id="name">
                </div>
                <div class="form-group mt-3">
                    <label for="exampleInputEmail1">Email *</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1">
                </div>
                <div class="form-group mt-3">
                    <label for="exampleInputPassword1">Пароль *</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                <div class="form-group mt-3">
                    <label for="exampleInputPassword1">Пітвердження паролю *</label>
                    <input type="password" class="form-control" name="confirm_password" id="exampleInputPassword1">
                </div>


                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="form-group d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary">Зареєструватися</button>
                    </div>
                    <div class="form-check form-group d-flex justify-content-end">
                        <a href="?url=auth/login">Вже маєте акаунт? Увійдіть</a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/app/views/layouts/footer.php'); ?>
