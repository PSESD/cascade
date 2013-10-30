<?php
/**
 * library/db/ActiveRecord.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package infinite
 */


namespace app\components\db;

trait ActiveRecordTrait {
	public function behaviors() {
		$behaviors = parent::behaviors();
		return array_merge($behaviors, [
			'Access' => [
				'class' => '\infinite\db\behaviors\Access',
			],
			'SearchTerm' => [
				'class' => '\infinite\db\behaviors\SearchTerm',
			]
		]);
	}
}


?>
