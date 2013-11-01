<?php
/**
 * ./app/config/environments/common/modules.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


return [
	'TypeAccount' => [
	    'class' => 'app\modules\TypeAccount\Module',
	],
	'TypeIndividual' => [
	    'class' => 'app\modules\TypeIndividual\Module',
	],
	'TypeEmailAddress' => [
		'class' => 'app\modules\TypeEmailAddress\Module',
    ],
    'TypePhoneNumber' => [
        'class' => 'app\modules\TypePhoneNumber\Module',
    ],
];
?>
