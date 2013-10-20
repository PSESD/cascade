<?php
$app = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app';
$env = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'env.php';

require_once($env);
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
	die('You are not allowed to access this file.');
}

require_once(__DIR__ . '/../config/environment.php');

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');
require_once(__DIR__ . '/../vendor/InfiniteCascade/yii2-infinite-core/library/Infinite.php');
Yii::importNamespaces(require(__DIR__ . '/../vendor/composer/autoload_namespaces.php'));

$config = require(__DIR__ . '/../config/web-test.php');

$application = new yii\web\Application($config);
$application->run();
