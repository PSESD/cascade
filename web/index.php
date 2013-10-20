<?php
if (!defined('INFINITE_APP_VERSION')) { define('INFINITE_APP_VERSION', 0); } // $VERSION:DO_NOT_TOUCH$

$app = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app';
$env = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'env.php';

if (!file_exists($env)) {
	header("Location: setup.php");
	exit;
}
require_once($env);

if(INFINITE_APP_VERSION > INFINITE_APP_INSTANCE_VERSION) {
	header("Location: /setup.php");
	exit;
}

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');
require_once(__DIR__ . '/../vendor/InfiniteCascade/yii2-infinite-core/library/Infinite.php');

Yii::importNamespaces(require(__DIR__ . '/../vendor/composer/autoload_namespaces.php'));

$configPath =  INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR .  'main.php';
$config = require_once($configPath);
$application = new yii\web\Application($config);
$application->run();
