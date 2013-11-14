<?php

namespace app\modules\SectionContact\modules\TypeEmailAddress\models;

/**
 * This is the model class for table "object_email_address".
 *
 * @property string $id
 * @property string $email_address
 * @property boolean $no_mailings
 * @property string $created
 * @property string $modified
 *
 * @property Registry $registry
 */
class ObjectEmailAddress extends \app\components\db\ActiveRecord
{
	use \app\components\objects\ActiveRecordTrait;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_email_address';
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
			[['email_address'], 'required'],
			[['no_mailings'], 'boolean'],
			[['created', 'modified'], 'unsafe'],
			[['id'], 'string', 'max' => 36],
			[['email_address'], 'string', 'max' => 255]
		];
	}


	/**
	 * @inheritdoc
	 */
	public function fieldSettings()
	{
		return [
			'email_address' => [],
			'no_mailings' => []
		];
	}


	/**
	 * @inheritdoc
	 */
	public function formSettings()
	{
		return [
			'email_address' => [],
			'no_mailings' => []
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'email_address' => 'Email Address',
			'no_mailings' => 'No Mailings',
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
