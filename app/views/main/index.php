<?php

/**
 * @var $books
 * @var $authors
 * @var $genres
 * @var $searchBooks
 */

?>

<main class="container">
    <div class="row">
        <div class="col-12 col-lg-6">
            <h4 class="mb-3">Книги</h4>
            <form id="form" class="needs-validation position-relative" method="POST"
                  action="/search">
                <div class="row g-3">
                    <div class="col-sm-8">
                        <label for="firstName" class="form-label">Название книги</label>
                        <input type="text"
                               class="form-control rounded-pill"
                               id="form-input"
                               name="search"
                               value="<?= $searchBooks['search'] ?? ''?>"
                               >
                    </div>

                    <div class="col-sm-4">
                        <label for="firstName" class="form-label">&nbsp;</label>
                        <button id="form-find" class="w-100 btn btn-primary btn-md rounded-pill" type="submit">
                            Поиск
                        </button>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mb-3 spoiler-block">
                    <div id="form-authors" class="col-6">
                        <h4 class="mb-3">Авторы</h4>
                        <?php
                        $searchAuthors = isset($searchBooks['authors']) ?
                            explode(',', $searchBooks['authors']) : [];

                        foreach ($authors as $author) {
                            $authorChecked = '';
                            $authorId = $author['id'];

                            if (in_array($authorId, $searchAuthors)) {
                                $authorChecked = 'checked="checked"';
                            }
                            ?>
                            <div class="form-check">
                                <input
                                        type="checkbox"
                                        name="author-<?= $authorId ?>"
                                        class="form-check-input rounded-pill"
                                        id="author-<?= $authorId ?>"
                                        value="author-<?= $authorId ?>"
                                    <?= $authorChecked ?>
                                >
                                <label class="form-check-label" for="author-<?= $author['id'] ?>">
                                    <?= $author['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>

                    <div id="form-genres" class="col-6">
                        <h4 class="mb-3">Жанры</h4>
                        <?php
                        $sessionGenres = isset($searchBooks['genres']) ?
                            explode(',', $searchBooks['genres']) : [];

                        foreach ($genres as $genre) {
                            $genreChecked = '';
                            $genreId = $genre['id'];

                            if (in_array($genreId, $sessionGenres)) {
                                $genreChecked = 'checked="checked"';
                            }
                            ?>
                            <div class="form-check">
                                <input type="checkbox"
                                       name="genre-<?= $genreId ?>"
                                       class="form-check-input rounded-pill"
                                       id="genre-<?= $genreId ?>"
                                       value="genre-<?= $genreId ?>"
                                    <?= $genreChecked ?>
                                >
                                <label class="form-check-label" for="genre-<?= $genre['id'] ?>">
                                    <?= $genre['name']; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <button id="form-spoiler" class="spoiler-button w-100 btn btn-info btn-md
                position-absolute b-5 text-white rounded-pill"
                        type="submit">
                    Спойлер
                </button>
                <hr class="my-4">
            </form>
            <?php
            $formClearClass = '';
            if (!empty($searchBooks['authors']) || !empty($searchBooks['genres']) || !empty
                ($searchBooks['search'])) {
                $formClearClass = ' none';
            }
            ?>
            <a id="form-clear" href="/clearAll" class="w-100 btn btn-primary rounded-pill btn-md<?=
            $formClearClass ?>"
               type="submit">
                Отчистить поиск
            </a>
        </div>
        <div id="books" class="col-12 col-lg-6">
            <?php foreach ($books as $book) { ?>
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-lg-block">
                        <img class="bd-placeholder-img"
                             width="100"
                             src="<?= $book['img'] ?>"
                             alt="<?= $book['name'] ?>">
                    </div>
                    <div class="col p-2 d-flex flex-column position-static">
                        <h4 class="mb-0">
                            <?= $book['name'] ?>
                        </h4>
                        <p class="card-text">
                            <?= mb_strimwidth($book['description'], 0, 40, "..."); ?>
                        </p>
                        <div class="mb-1 text-muted">автор: <?= $book['authors'] ?></div>
                        <div class="mb-1 text-muted">жанр: <?= $book['genres'] ?></div>
                        <a href="product/<?= $book['id'] ?>" class="stretched-link"></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</main>