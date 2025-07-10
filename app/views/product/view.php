<?php

/**
 * @var $book
 */

?>
<div class="container">
    <a href="/" class="btn btn-outline-primary me-2">Главная</a>
    <hr class="my-4">
    <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col-auto d-lg-block">
            <img class="bd-placeholder-img"
                 width="340"
                 src="<?= $book['img'] ?>"
                 alt="<?= $book['name'] ?>">
        </div>
        <div class="col p-4 d-flex flex-column position-static">
            <h3 class="mb-0">
                <?= $book['name'] ?>
            </h3>
            <p class="card-text">
                <?= $book['description'] ?>
            </p>
            <div class="mb-1 text-muted">автор: <?= $book['authors'] ?></div>
            <div class="mb-1 text-muted">жанр: <?= $book['genres'] ?></div>
        </div>
    </div>
</div>