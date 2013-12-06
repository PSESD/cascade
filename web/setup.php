<?php
/**
 * ./web/setup.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
define('YII_DEBUG', true);

defined('INFINITE_APP_INSTALL_PATH') OR define('INFINITE_APP_INSTALL_PATH', dirname(dirname(__FILE__)));
defined('INFINITE_APP_APP_PATH') OR define('INFINITE_APP_APP_PATH', INFINITE_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'cascade');
defined('INFINITE_APP_SETUP_PATH') OR define('INFINITE_APP_SETUP_PATH', INFINITE_APP_APP_PATH . DIRECTORY_SEPARATOR . 'setup');
defined('INFINITE_APP_VENDOR_PATH') OR define('INFINITE_APP_VENDOR_PATH', INFINITE_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'vendor');

register_shutdown_function(function() {
	if (!error_get_last()) { return; }
	echo 'Boo! <pre>';
	print_r(error_get_last());
	echo '</pre>';
});

ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);
require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'autoload.php');
require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'yiisoft/yii2/yii/Yii.php');
//require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'InfiniteCascade/yii2-infinite-core/library/infinite/Infinite.php');
Yii::setAlias('@cascade', INFINITE_APP_APP_PATH);

$config = array(
	'basePath' => INFINITE_APP_INSTALL_PATH,
	'applicationPath' => INFINITE_APP_APP_PATH,
	'name' => 'Application Template',
	'applicationNamespace' => 'cascade'
);
try {
	$app = cascade\setup\Setup::createSetupApplication($config);
	$app->run();
} catch (\Exception $e) {
	$backtrace = $e->getTrace();
	$backtrace = $backtrace[1];

	echo $e->getMessage();
	echo '<pre>';
	foreach ($e->getTrace() as $backtrace) {
		if (!isset($backtrace['file'])) { continue; }
		echo $backtrace['file'].':'. $backtrace['line'] ."\n";
	}
	echo '</pre>';
	exit;
	$app->params['error'] = true;
	$app->params['message'] = $e->getMessage() .' ('. $backtrace['file'].':'. $backtrace['line'].')';
	$app->render('message');
}

?>
