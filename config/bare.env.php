<?php
defined('YII_DEBUG') 					|| define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL')				|| define('YII_TRACE_LEVEL', 3);
defined('YII_ENV')						|| define('YII_ENV', 'dev');

defined('CANIS_APP_ID')					|| define('CANIS_APP_ID', 'cascade');
defined('CANIS_APP_NAME')				|| define('CANIS_APP_NAME', 'Cascade');
defined('CANIS_APP_NAMESPACE')			|| define('CANIS_APP_NAMESPACE', 'cascade');

defined('CANIS_APP_INSTANCE_VERSION')	|| define('CANIS_APP_INSTANCE_VERSION', false);
defined('CANIS_APP_INSTALL_PATH')		|| define('CANIS_APP_INSTALL_PATH', dirname(__DIR__));
defined('CANIS_APP_VENDOR_PATH')		|| define('CANIS_APP_VENDOR_PATH', CANIS_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'vendor');
defined('CANIS_APP_PATH') 				|| define('CANIS_APP_PATH', CANIS_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'canis' . DIRECTORY_SEPARATOR . 'cascade-lib' . DIRECTORY_SEPARATOR . 'lib');
defined('CANIS_APP_CONFIG_PATH')		|| define('CANIS_APP_CONFIG_PATH', CANIS_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'config');

defined('CANIS_APP_DATABASE_HOST')		|| define('CANIS_APP_DATABASE_HOST', '');
defined('CANIS_APP_DATABASE_PORT')		|| define('CANIS_APP_DATABASE_PORT', '');
defined('CANIS_APP_DATABASE_USERNAME')	|| define('CANIS_APP_DATABASE_USERNAME', '');
defined('CANIS_APP_DATABASE_PASSWORD')	|| define('CANIS_APP_DATABASE_PASSWORD', '');
defined('CANIS_APP_DATABASE_DBNAME')	|| define('CANIS_APP_DATABASE_DBNAME', 'cascade');

defined('CANIS_APP_REDIS_HOST')			|| define('CANIS_APP_REDIS_HOST', '');
defined('CANIS_APP_REDIS_PORT')		|| define('CANIS_APP_REDIS_PORT', 6379);
defined('CANIS_APP_REDIS_DATABASE')		|| define('CANIS_APP_REDIS_DATABASE', 0);
?>