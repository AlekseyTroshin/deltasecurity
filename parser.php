<?php

$url = "https://litlife.club/popular_books?page=2";

$dom = new DOMDocument();
@$dom->loadHTMLFile($url);
$xpath = new DOMXPath($dom);
$blocks = $xpath->query("//div[contains(@class, 'book card')]");


$resultsParse = [];
foreach ($blocks as $block) {
    $item = [];

    $img = $xpath->query(".//img[contains(@class, 'lazyload rounded')]", $block);
    $item['img'] = $img->length > 0 ? trim($img[0]->attributes['data-src']->value) : '';

    $title = $xpath->query(".//h3[contains(@class, 'break-words h5')]", $block);
    $item['title'] = $title->length > 0 ? trim($title[0]->nodeValue) : '';

    $authorsDOM = $xpath->query(".//*[contains(@class, 'author name')]", $block);
    $authors = [];
    foreach ($authorsDOM as $author) {
        $authors[] = $author->textContent;
    }
    $item['authors'] = $authors;

    $genresDOM = $xpath->query(".//*[contains(@class, 'author name')]", $block);
    $genresDOM = $genresDOM[0]->parentNode->nextElementSibling;

    $genres = explode(',', trim(ltrim(trim($genresDOM->textContent), 'Жанры:')));
    foreach ($genres as &$genre) {
        $genre = trim($genre);
    }
    $item['genres'] = $genres;

    $description = $xpath->query(".//div[contains(@class, 'mt-3')]", $block);
    $description = $description[0]->textContent ?? '';
    $item['description'] = trim(rtrim(trim($description), 'далее'));

    $resultsParse[] = $item;
}


$results = [];
$books = [];
$authors = [];
$genres = [];
$bookAuthors = [];
$bookGenres = [];
$countAuthors = 1;
$countGenres = 1;

for ($i = 0; $i < count($resultsParse); $i++) {
    $book = [];
    $id = $i + 1;

    $book['id'] = $id;
    $book['name'] = $resultsParse[$i]['title'];
    $book['description'] = $resultsParse[$i]['description'];
    $book['img'] = $resultsParse[$i]['img'];

    foreach ($resultsParse[$i]['authors'] as $item) {
        if (!isset($authors[$item])) {
            $authors[$item] = $countAuthors;
            $countAuthors++;
        }

        $bookAuthors[] = ['book' => $id, 'author' => $authors[$item]];
    }

    foreach ($resultsParse[$i]['genres'] as $item) {
        if (!isset($genres[$item])) {
            $genres[$item] = $countGenres;
            $countGenres++;
        }

        $bookGenres[] = ['book' => $id, 'genre' => $genres[$item]];
    }

    $books[] = $book;
}


$authors = array_keys($authors);
$genres = array_keys($genres);












