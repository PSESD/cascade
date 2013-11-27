<?php
namespace app\modules\SectionContact;

class Module extends \app\components\section\Module {
	protected $_title = 'Contact';
	
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