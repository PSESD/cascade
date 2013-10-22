<?php
defined('YII_ENV') or define('YII_ENV', 'test');

$app = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app';
$env = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'env.php';

if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
	die('You are not allowed to access this file.');
}
if (!file_exists($env)) {
	header("Location: setup.php");
	exit;
}
require_once($env);

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');
require_once(__DIR__ . '/../vendor/InfiniteCascade/yii2-infinite-core/library/infinite/Infinite.php');
Yii::importNamespaces(require(__DIR__ . '/../vendor/composer/autoload_namespaces.php'));

$configPath =  INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR .  'web-test.php';
$config = require_once($configPath);
$application = new yii\web\Application($config);
$application->run();
