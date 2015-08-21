<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__).'/../module/'));
define("APPLICATION_ENV",'development');
define("_CRONJOB_",true);
require(APPLICATION_PATH.'/../public/index.php');