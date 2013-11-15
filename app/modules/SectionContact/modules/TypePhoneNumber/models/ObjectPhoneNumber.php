<?php

namespace app\modules\SectionContact\modules\TypePhoneNumber\models;

/**
 * This is the model class for table "object_phone_number".
 *
 * @property string $id
 * @property string $phone
 * @property string $extension
 * @property boolean $no_call
 * @property string $created
 * @property string $modified
 *
 * @property Registry $registry
 */
class ObjectPhoneNumber extends \app\components\db\ActiveRecord
{
	use \app\components\types\ActiveRecordTrait;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_phone_number';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), []);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['phone', 'extension'], 'required'],
			[['no_call'], 'boolean'],
			[['created', 'modified'], 'unsafe'],
			[['id'], 'string', 'max' => 36],
			[['phone'], 'string', 'max' => 100],
			[['extension'], 'string', 'max' => 15]
		];
	}


	/**
	 * @inheritdoc
	 */
	public function fieldSettings()
	{
		return [
			'phone' => [],
			'extension' => [],
			'no_call' => []
		];
	}


	/**
	 * @inheritdoc
	 */
	public function formSettings()
	{
		return [
			'phone' => [],
			'extension' => [],
			'no_call' => []
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'phone' => 'Phone',
			'extension' => 'Extension',
			'no_call' => 'No Call',
			'created' => 'Created',
			'modified' => 'Modified',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getRegistry()
	{
		return $this->hasOne('Registry', ['id' => 'id']);
	}
}
