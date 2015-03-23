<?php
$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'env.php';
if (!file_exists($envFile)) {
	if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'setup.php')) {
		throw new \Exception("Environment has not been set up!");
	}
	header("Location: setup.php");
	exit;
}
require_once($envFile);

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'yiisoft'. DIRECTORY_SEPARATOR . 'yii2'. DIRECTORY_SEPARATOR . 'Yii.php');

Yii::setAlias('@cascade', CANIS_APP_PATH);
$config = require(CANIS_APP_CONFIG_PATH . DIRECTORY_SEPARATOR . 'web.php');

(new canis\web\Application($config))->run();
