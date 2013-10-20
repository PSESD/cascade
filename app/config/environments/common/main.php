<?php
/**
 * ./app/config/environments/common/main.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


return array(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..',
	// preloading 'log' component
	'preload' => array('log'),
	// autoloading model and component classes
	'language' => 'en',

	'modules' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'modules.php'),

	// application components
	'components' => array(
		'db' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "database.php"),
		'cache' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'cache.php'),
		'clientScript' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'client_script.php'),
		'request' => array(
			'class' => '\infinite\web\request',
			'enableCsrfValidation' => true,
			'enableCookieValidation' => true,
		),
		'user' => array(
			'class' => '\infinite\web\user',
			'enableAutoLogin' => false,
			'identityClass' => '\app\models\User',
			'loginUrl' => array('/app/login'),
		),
		'roleEngine' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'roles.php'),
		'session' => array(
			'class' => '\infinite\web\DbSession',
			//'connectionID' => 'db',
			//'autoCreateSessionTable' => false,
			'sessionTable' => 'http_session',
			'timeout' => '4000' // be sure to change yiic.php too
		),
		
		'gk' => array('class' => 'RGatekeeper'),
		'urlManager' => array(
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				// a standard rule mapping '/' to 'site/index' action
				'' => 'app/index',

				// a standard rule mapping '/login' to 'site/login', and so on
				'<action:(login|logout|forgot|search|quick)>' => 'app/<action>',

				'<action:(view)>/<id:\S+>/<section:\S+>' => 'app/<action>',
				'<action:(delete|update|view|watch|unwatch|setPrimary)>/<id:\S+>' => 'app/<action>',
				'<action:(create|link|browse)>/<module:\w+>' => 'app/<action>',

				'<action:(browse|suggest|delete)>' => 'app/<action>',

				'<controller:\w+>/<action:\w+>/<id:\S+>' => '<controller>/<action>',
				// a standard rule to handle 'post/update' and so on
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		),
		'log' => [
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
