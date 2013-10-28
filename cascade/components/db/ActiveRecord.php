<?php
/**
 * library/db/ActiveRecord.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package infinite
 */


namespace app\components\db\ActiveRecord;
use \infinite\db\ActiveRecord;

class ActiveRecord extends \infinite\db\ActiveRecord {
	public function behaviors() {
		return array_merge(parent::behaviors(), [
			'Acl' => [
				'class' => '\infinite\db\behaviors\Acl',
			],
			'SearchTerm' => [
				'class' => '\infinite\db\behaviors\SearchTerm',
			]
		]);
	}
}


?>
