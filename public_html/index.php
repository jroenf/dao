<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../zends/Zend-1.10.8/library'),
    get_include_path(),
)));
/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini',APPLICATION_ENV,array('allowModifications'=>true));
/**
 * Dit is waar je je bepaalt wat je data acces object wordt. Tabledefinitions, of een xml-rpc client die een server aanspreekt.
 */
//$dao = new Zend_Config_Ini(APPLICATION_PATH.'/configs/dao-tabledefinitions.ini',APPLICATION_ENV);
$dao = new Zend_Config_Ini(APPLICATION_PATH.'/configs/dao-xmlrpc.ini',APPLICATION_ENV);
$config->merge($dao);

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
     $config
);
$application->bootstrap()
            ->run();