<?php
/**
 * zapne session a nadefinuje zkratky k některým složkám
 */
session_start();
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);
define('CSS', ROOT . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . "style.css");


// načte nastavení v config/config.php
require APP . 'config/config.php';

// načte helper.php
require APP . 'libs/helper.php';

// načte application.php a controller.php
require APP . 'core/application.php';
require APP . 'core/controller.php';

// nastartuje celou aplikaci
$app = new Application();
