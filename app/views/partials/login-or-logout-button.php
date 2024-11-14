<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="?url=auth/login"><button type="button" class="btn btn-primary">Увійти</button></a>
<?php else:  ?>
    <a href="?url=auth/logout"><button type="button" class="btn btn-primary">Вихід</button></a>
<?php endif; ?>
