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

if (APPLICATION_ENV == 'development') {
    define('MAIN_DOMAIN', 'http://tirbox.local/');
} elseif (APPLICATION_ENV == 'dev') {
    define('MAIN_DOMAIN', 'http://dev.tirbox.ro/');
} else {
    define('MAIN_DOMAIN', 'http://www.tirbox.ro/');
}



// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
