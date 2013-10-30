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
		if (isset($behaviors['Registry'])) {
			$behaviors['Registry']['class'] = '\app\components\db\behaviors\Registry';
		}
		if (isset($behaviors['Relatable'])) {
			$behaviors['Relatable']['class'] = '\app\components\db\behaviors\Relatable';
		}
		return array_merge($behaviors, [
			'Access' => [
				'class' => '\app\components\db\behaviors\Access',
			],
			'SearchTerm' => [
				'class' => '\infinite\db\behaviors\SearchTerm',
			]
		]);
	}
}


?>
