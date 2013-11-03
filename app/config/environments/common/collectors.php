<?php
return [
	'roles' => include(INFINITE_APP_ENVIRONMENT_PATH . DIRECTORY_SEPARATOR . 'roles.php'),
	'widgets' => [
		'class' => '\app\components\web\widgets\Collector',
	],
	'types' => [
		'class' => '\app\components\types\Collector',
	],
	'sections' => [
		'class' => '\app\components\sections\Collector',
	],
	'dataInterfaces' => [
		'class' => '\app\components\dataInterface\Collector',
	]
];
?>