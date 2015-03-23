<?php
define('CANIS_SETUP', true);

$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'env.php';
if (!file_exists($envFile)) {
	$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bare.env.php';
}
require_once($envFile);

require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'yiisoft'. DIRECTORY_SEPARATOR . 'yii2'. DIRECTORY_SEPARATOR . 'Yii.php');

Yii::setAlias('@cascade', CANIS_APP_PATH);
$config = [
	'basePath' => CANIS_APP_INSTALL_PATH,
	'applicationPath' => Yii::getAlias('@cascade'),
	'name' => 'Cascade',
	'applicationNamespace' => 'cascade'
];
try {
	cascade\setup\Setup::createSetupApplication($config)->run();
} catch (\Exception $e) {
	$backtrace = $e->getTrace();
	var_dump($e);
	$backtrace = $backtrace[1];

	echo $e->getMessage();
	echo '<pre>';
	foreach ($e->getTrace() as $backtrace) {
		if (!isset($backtrace['file'])) { continue; }
		echo $backtrace['file'].':'.$backtrace['function'].':'. $backtrace['line'] ."\n";
	}
	echo '</pre>';
	exit;
	$app->params['error'] = true;
	$app->params['message'] = $e->getMessage() .' ('. $backtrace['file'].':'. $backtrace['line'].')';
	$app->render('message');
}