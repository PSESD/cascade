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
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..',
	// preloading 'log' component
	'preload' => array('log', 'debug'),
	// autoloading model and component classes
	'language' => 'en',

	'modules' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'modules.php'),
	'extensions' => include(INFINITE_APP_VENDOR_PATH . DIRECTORY_SEPARATOR . 'yii-extensions.php'),

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
		'view' => [
			'class' => '\infinite\base\view',
		],
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
		
		'gk' => array('class' => '\infinite\security\Gatekeeper'),
		'urlManager' => array(
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				// a standard rule mapping '/' to 'site/index' action
				'' => 'app/index',

				// a standard rule to handle 'post/update' and so on
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		),
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
