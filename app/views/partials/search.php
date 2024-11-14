  <div class="col-10">
        <form method="GET" action="?url=recipes/index">
            <div class="input-group mb-3 d-flex align-items-center">
                <input type="text" class="form-control" name="search" placeholder="Пошук" aria-label="Пошук" aria-describedby="basic-addon2" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Пошук</button>
                </div>

                <?php if (isset($_GET['search'])): ?>
                    <a href="?url=recipes/index" class="btn-close ms-2"></a>
                <?php endif; ?>

            </div>
        </form>
    </div>
