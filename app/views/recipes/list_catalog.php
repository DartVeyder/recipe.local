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
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id']== $recipe['user_id']): ?>
                        <div class="action">
                            <a href="?url=recipes/edit/<?php echo $recipe['id']; ?>" class="fas fa-edit text-info"></a>
                            <a href="?url=recipes/delete/<?php echo $recipe['id']; ?>" class="fas fa-trash-alt text-danger ml-3"></a>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title"><?php echo $recipe['title']; ?></h4>
                        <?php if (isset($_SESSION['user_id'])  ): ?>
                            <?php if (  $recipe['is_in_wishlist']): ?>
                                <a href="?url=wishlist/delete/<?php echo $recipe['is_in_wishlist']; ?>"><i class="fas fa-heart"></i></a>
                            <?php else:  ?>
                                <a href="?url=wishlist/create&user_id=<?php echo $_SESSION['user_id']; ?>&recipe_id=<?php echo $recipe['id']; ?>"><i class="far fa-heart"></i></a>
                            <?php endif; ?>


                        <?php endif; ?>
                    </div>
                    <p class="card-text"><?php echo $recipe['description']; ?></p>
                    <a href=" ?url=recipes/show/<?php echo $recipe['id']; ?>" class="btn btn-info">Читати</a>

                </div>
                <div class="card-footer text-muted  bg-transparent border-top-0">
                    <div>
                        <div class="views d-flex  justify-content-between">
                            Дата створення: <?php echo $created_at->format('d.m.Y'); ?>
                            <div class="stats ">
                                <i class="far fa-eye"></i> <?php echo $recipe['views']; ?>
                                <!-- <i class="far fa-comment"></i> <?php echo $recipe['total_reviews']; ?>-->
                            </div>
                        </div>

                    </div>
                    <div>
                        <p> Автор: <?php echo $recipe['user_name'];?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
