<?php
if (!defined('INFINITE_APP_VERSION')) { define('INFINITE_APP_VERSION', 0); } // $VERSION:DO_NOT_TOUCH$
defined('INFINITE_APP_INSTALL_PATH') OR define('INFINITE_APP_INSTALL_PATH', dirname(dirname(__FILE__)));
defined('INFINITE_APP_APP_PATH') OR define('INFINITE_APP_APP_PATH', INFINITE_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'cascade');
defined('INFINITE_APP_SETUP_PATH') OR define('INFINITE_APP_SETUP_PATH', INFINITE_APP_APP_PATH . DIRECTORY_SEPARATOR . 'setup');
defined('INFINITE_APP_VENDOR_PATH') OR define('INFINITE_APP_VENDOR_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor');

$app = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app';
$env = INFINITE_APP_APP_PATH . DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'env.php';

if (!file_exists($env)) {
	header("Location: setup.php");
	exit;
}
require_once($env);

if(INFINITE_APP_VERSION > INFINITE_APP_INSTANCE_VERSION) {
	header("Location: /setup.php");
	exit;
}


require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'autoload.php');
require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'yiisoft/yii2/yii/Yii.php');
//require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'InfiniteCascade/yii2-infinite-core/library/infinite/Infinite.php');

$configPath =  INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR .  'web-test.php';
$config = require_once($configPath);
$application = new yii\web\Application($config);
$application->run();
