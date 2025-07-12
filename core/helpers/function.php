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
