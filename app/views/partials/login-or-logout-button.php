<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="?url=auth/login"><button type="button" class="btn btn-primary">Увійти</button></a>
<?php else:  ?>

    <div><span class="me-3"><?php echo $_SESSION['user_name']  ?></span><a href="?url=auth/logout"><button type="button" class="btn btn-primary">Вихід</button></a> </div>
<?php endif; ?>
