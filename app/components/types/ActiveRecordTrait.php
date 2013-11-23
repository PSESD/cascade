<?php
/**
 * library/db/ActiveRecord.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package infinite
 */


namespace app\components\types;

trait ActiveRecordTrait {
	public function behaviors() {
		return [
			'Registry' => [
				'class' => '\infinite\db\behaviors\Registry',
			],
			'Relatable' => [
				'class' => '\infinite\db\behaviors\Relatable',
			]
		];
	}

	public function getUrl($action = 'view') {
		return ['object/view', 'id' => $this->primaryKey];
	}
}


?>
