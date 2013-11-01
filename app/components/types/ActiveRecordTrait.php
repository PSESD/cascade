<?php
/**
 * library/db/ActiveRecord.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package infinite
 */


namespace app\components\object;

trait ActiveRecordTrait {
	public function behaviors() {
		return array_merge(parent::behaviors(), [
			'Registry' => [
				'class' => '\infinite\db\behaviors\Registry',
			],
			'Relatable' => [
				'class' => '\infinite\db\behaviors\Relatable',
			]
		]);
	}
}


?>
