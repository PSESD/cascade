<?php
/**
 * ./app/config/environments/common/console.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
$modules = include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'modules.php');
unset($modules['debug']);
return [
	'id' => 'cascade',
	'name' => 'Cascade',
	'basePath' => dirname(dirname(dirname(dirname(__FILE__)))),
	'preload' => array('log'),
	'language' => 'en',
	'controllerPath' => '@app/commands',
	'controllerNamespace' => 'app\commands',
	'controllerMap' => array(
		'migrate' => '\infinite\console\controllers\MigrateController',
		'sprite' => '\infinite\console\controllers\SpriteController'
	),
	'modules' => $modules,
	'extensions' => include(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'yii-extensions.php'),
	// application components
	'components' => [ 
		'cache' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'cache.php'),
		'db' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "database.php"),
		'roleEngine' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'roles.php'),
		'gk' => array('class' => '\infinite\security\Gatekeeper'),
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
	],
	'params' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "params.php"),
];