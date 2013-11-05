<?php
namespace app\modules\SectionContact;

class Module extends \app\components\section\Module {
	
	public function getSubmodules() {
		return [
			'TypePhoneNumber' => [
				'class' => 'app\modules\SectionContact\modules\TypePhoneNumber\Module',
			],
			'TypeEmailAddress' => [
				'class' => 'app\modules\SectionContact\modules\TypeEmailAddress\Module',
			],
		];
	}
}
?>