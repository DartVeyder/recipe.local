<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="?url=auth/login"><button type="button" class="btn btn-primary">Увійти</button></a>
<?php else:  ?>
    <div><a style="text-decoration: none; color: #000; margin-right: 10px" href="?url=wishlist/index"><i class="far fa-heart " style="font-size: 25px;"></i>  </a> <span class="me-3"><?php echo $_SESSION['user_name']  ?></span><a href="?url=auth/logout"><button type="button" class="btn btn-primary">Вихід</button></a> </div>
<?php endif; ?>
