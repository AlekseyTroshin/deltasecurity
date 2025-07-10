<?php

function debug($data, $exit = false)
{
    echo '<br><pre>' . print_r($data, 1) . '</pre><br><br>';

    if ($exit) {
        exit;
    }
}

function hsc($str)
{
    return htmlspecialchars($str);
}

function redirect(string $http): void
{

    header("Location: {$http}");
    exit;
}

function get(string $key): string|int|float
{
    $param = $key;
    return $_GET[$param] ?? '';
}

function post(string $key): string|int|float
{
    $param = $key;
    return  $_POST[$param] ?? '';
}

function isAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function sessionsGet(string $name, mixed $field = NULL): mixed
{
    if ($field) {
        return $_SESSION[$name][$field] ?? NULL;
    } else {
        return $_SESSION[$name] ?? NULL;
    }
}

function sessionsSet(string $name, mixed $item): void
{
    $_SESSION[$name] = $item;
}

function sessionsClear(string $name, string $field = NULL): void
{
    if ($field) {
        unset($_SESSION[$name][$field]);
    } else {
        unset($_SESSION[$name]);
    }
}

function str2url($str): string
{
    debug($str, 1);
    $str = preg_replace('/%20/', '_', $str);
    $str = trim($str, "-");
    return $str;
}

function rus2translit($string): string
{

    $converter = array(

        'а' => 'a', 'б' => 'b', 'в' => 'v',

        'г' => 'g', 'д' => 'd', 'е' => 'e',

        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',

        'и' => 'i', 'й' => 'y', 'к' => 'k',

        'л' => 'l', 'м' => 'm', 'н' => 'n',

        'о' => 'o', 'п' => 'p', 'р' => 'r',

        'с' => 's', 'т' => 't', 'у' => 'u',

        'ф' => 'f', 'х' => 'h', 'ц' => 'c',

        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',

        'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',

        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',


        'А' => 'A', 'Б' => 'B', 'В' => 'V',

        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',

        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',

        'И' => 'I', 'Й' => 'Y', 'К' => 'K',

        'Л' => 'L', 'М' => 'M', 'Н' => 'N',

        'О' => 'O', 'П' => 'P', 'Р' => 'R',

        'С' => 'S', 'Т' => 'T', 'У' => 'U',

        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',

        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',

        'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',

        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',

    );

    return strtr($string, $converter);
}
