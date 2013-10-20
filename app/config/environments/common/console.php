<?php
/**
 * ./app/config/environments/common/console.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
return [
	'basePath' => dirname(dirname(dirname(dirname(__FILE__)))),
	'preload' => array('log'),
	'language' => 'en',
	'controllerMap' => array(
		'migrate' => '\infinite\console\controllers\MigrateController'
	),
	'modules' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'modules.php'),
	// application components
	'components' => [
		'cache' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'cache.php'),
		'db' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "database.php"),
		'roleEngine' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'roles.php'),
		'gk' => array('class' => '\infinite\security\Gatekeeper'),
		'log' => [],
	],
	'params' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . "params.php"),
];