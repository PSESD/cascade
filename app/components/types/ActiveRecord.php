<?php
namespace app\components\types;

class ActiveRecord extends \app\components\db\ActiveRecord {
	use ActiveRecordTrait {
		behaviors as baseBehaviors;
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), self::baseBehaviors(), []);
	}
}
?>