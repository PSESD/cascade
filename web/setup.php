<?php
/**
 * ./web/setup.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

register_shutdown_function(function() {
	if (!error_get_last()) { return; }
	echo '<pre>';
	print_r(error_get_last());
	echo '</pre>';
});

ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);
define('YII_DEBUG', true);

defined('INFINITE_APP_INSTALL_PATH') OR define('INFINITE_APP_INSTALL_PATH', dirname(dirname(__FILE__)));
defined('INFINITE_APP_APP_PATH') OR define('INFINITE_APP_APP_PATH', INFINITE_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'app');
defined('INFINITE_APP_SETUP_PATH') OR define('INFINITE_APP_SETUP_PATH', INFINITE_APP_APP_PATH . DIRECTORY_SEPARATOR . 'setup');


require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');
require_once(__DIR__ . '/../vendor/InfiniteCascade/yii2-infinite-core/library/infinite/Infinite.php');

Yii::importNamespaces(require(__DIR__ . '/../vendor/composer/autoload_namespaces.php'));
Yii::setAlias('app', INFINITE_APP_APP_PATH);

$config = array(
	'basePath' => INFINITE_APP_INSTALL_PATH,
	'name' => 'Application Template'
);

app\setup\Setup::createSetupApplication($config)->run();

?>
