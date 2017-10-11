<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));



// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('PUBLIC_IMG_PATH', __DIR__ . '/images/');
define('TBVER', '1.1');

if (APPLICATION_ENV !== 'production' || 1==2) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    ini_set('display_errors', 'On');
} else {
    error_reporting(0);
    ini_set('display_errors', 'Off');
}



if (APPLICATION_ENV == 'development') {
    define('MAIN_DOMAIN', 'http://tirbox.local/');
} elseif (APPLICATION_ENV == 'dev') {
    define('MAIN_DOMAIN', 'http://dev.tirbox.ro/');
    header('Location: http://www.tirbox.ro'); // redirect for stop using dev
} else {
    define('MAIN_DOMAIN', 'http://www.tirbox.ro/');
    if (strpos($_SERVER['HTTP_HOST'], 'www.') === false) {
        header('Location: '.MAIN_DOMAIN);
    }
//    var_dump($_SERVER['HTTP_HOST']);
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

try {


// Setup autoloading
    require 'init_autoloader.php';

// Run the application!
    Zend\Mvc\Application::init(require 'config/application.config.php')->run();
} catch (Exception $e) {
    var_dump($e->getMessage());
    var_dump($e->getFile());
    var_dump($e->getTraceAsString());
}