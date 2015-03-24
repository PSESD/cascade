<?php
define('CANIS_SETUP', true);
require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'yiisoft'. DIRECTORY_SEPARATOR . 'yii2'. DIRECTORY_SEPARATOR . 'Yii.php');
if (!class_exists('canis\base\ApplicationEngine')) {
	throw new \Exception('Canis core libraries have not been installed. Have you ran `composer install`?');
}
canis\base\ApplicationEngine::runSetupApplication(dirname(__DIR__));