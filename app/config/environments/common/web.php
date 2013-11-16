<?php
/**
 * ./app/config/environments/common/main.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

return array(
	'id' => 'cascade',
	'name' => 'Cascade',
	'basePath' => INFINITE_APP_APP_PATH,
	'vendorPath' => INFINITE_APP_INSTALL_PATH . DIRECTORY_SEPARATOR . 'vendor',
	// preloading 'log' component
	'preload' => array('log', 'collectors'),
	// autoloading model and component classes
	'language' => 'en',
	'modules' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'modules.php'),
	'extensions' => include(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'yiisoft'. DIRECTORY_SEPARATOR . 'extensions.php'),

	// application components
	'components' => array(
		'db' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "database.php"),
		'cache' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'cache.php'),
		'collectors' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'collectors.php'),
		'request' => array(
			'class' => '\infinite\web\request',
			'enableCsrfValidation' => true,
			'enableCookieValidation' => true,
		),
		'view' => [
			'class' => '\infinite\web\View',
		],
		'user' => array(
			'class' => '\infinite\web\User',
			'enableAutoLogin' => false,
			'identityClass' => '\app\models\User',
			'loginUrl' => array('/app/login'),
		),
		'gk' => array('class' => '\infinite\security\Gatekeeper'),
		'session' => array(
			'class' => '\infinite\web\DbSession',
			//'connectionID' => 'db',
			//'autoCreateSessionTable' => false,
			'sessionTable' => 'http_session',
			'timeout' => '4000' // be sure to change yiic.php too
		),
		
		'urlManager' => array(
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				// a standard rule mapping '/' to 'site/index' action
				'' => 'object/index',
				'<action:(view)>/<id:\S+>' => 'object/<action>',
				// a standard rule to handle 'post/update' and so on
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		),
		'assetManager' => [
			'linkAssets' => false
		],
		'log' => [
			'class' => 'yii\log\Logger',
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
	),
	'params' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "params.php"),
);
