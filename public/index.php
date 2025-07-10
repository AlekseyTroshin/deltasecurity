<?php

if (PHP_MAJOR_VERSION < 8) {
    exit("Необходимо версия PHP >= 8");
}



require_once dirname(__DIR__) . '/config/init.php';

use core\App;

new App();