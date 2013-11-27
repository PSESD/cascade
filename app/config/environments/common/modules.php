<?php
/**
 * ./app/config/environments/common/modules.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


return [
	'WidgetWatching' => [
	    'class' => 'app\modules\WidgetWatching\Module',
	],
	'TypeAccount' => [
	    'class' => 'app\modules\TypeAccount\Module',
	    'title' => 'Organizations'
	],
	'TypeIndividual' => [
	    'class' => 'app\modules\TypeIndividual\Module',
	],
	'SectionContact' => [
		'class' => 'app\modules\SectionContact\Module',
    ],
];
?>
