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
	'TypePostalAddress' => [
	    'class' => 'app\modules\TypePostalAddress\Module',
	],
	'TypePhoneNumber' => [
		'class' => 'app\modules\TypePhoneNumber\Module',
    ],
	'TypeEmailAddress' => [
		'class' => 'app\modules\TypeEmailAddress\Module',
    ],
    'TypeWebAddress' => [
        'class' => 'app\modules\TypeWebAddress\Module',
    ],
];
?>
