<?php

define("ROOT", dirname(__DIR__));
const DEBUG = 0;
const WWW = ROOT . '/public';
const APP = ROOT . '/app';
const CORE = ROOT . '/core';
const HELPERS = ROOT . '/core/helpers';
const LOGS = ROOT . '/tmp/logs';
const CONFIG = ROOT . '/config';
const CONTROLLERS = 'app\controllers';
const MODELS = 'app\models';
const VIEWS =  APP . '/views';
const PATH = 'http://localhost:8888';


require_once ROOT . '/vendor/autoload.php';
require_once HELPERS . '/function.php';