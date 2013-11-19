<?php
/**
 * ./web/setup.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
define('YII_DEBUG', true);

defined('INFINITE_APP_INSTALL_PATH') OR define('INFINITE_APP_INSTALL_PATH', dirname(dirname(__FILE__)));
defined('INFINITE_APP_APP_PATH') OR define('INFINITE_APP_APP_PATH', INFINITE_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'app');
defined('INFINITE_APP_SETUP_PATH') OR define('INFINITE_APP_SETUP_PATH', INFINITE_APP_APP_PATH . DIRECTORY_SEPARATOR . 'setup');
defined('INFINITE_APP_VENDOR_PATH') OR define('INFINITE_APP_VENDOR_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor');


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
require_once(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'InfiniteCascade/yii2-infinite-core/library/infinite/Infinite.php');

Yii::setAlias('@app', INFINITE_APP_APP_PATH);

$config = array(
	'basePath' => INFINITE_APP_INSTALL_PATH,
	'name' => 'Application Template'
);
try {
	$app = \app\setup\Setup::createSetupApplication($config);
	$app->run();
} catch (\Exception $e) {
	$app->params['error'] = true;
	$app->params['message'] = $e->getMessage();
	$app->render('message');
}

?>
