<?php

namespace app\modules\TypeAccount\models;

/**
 * This is the model class for table "object_account".
 *
 * @property string $id
 * @property string $name
 * @property string $alt_name
 * @property string $created
 * @property string $created_user_id
 * @property string $modified
 * @property string $modified_user_id
 * @property string $deleted
 * @property string $deleted_user_id
 *
 * @property Registry $registry
 * @property User $createdUser
 * @property User $deletedUser
 * @property User $modifiedUser
 */
class ObjectAccount extends \app\components\db\ActiveRecord
{
	use \app\components\types\ActiveRecordTrait;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_account';
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
			[['name'], 'required'],
			//[['created', 'modified', 'deleted'], 'unsafe'],
			[['id', 'created_user_id', 'modified_user_id', 'deleted_user_id'], 'string', 'max' => 36],
			[['name', 'alt_name'], 'string', 'max' => 255]
		];
	}


	/**
	 * @inheritdoc
	 */
	public function fieldSettings()
	{
		return [
			'name' => [],
			'alt_name' => []
		];
	}


	/**
	 * @inheritdoc
	 */
	public function formSettings($name, $settings = [])
	{
		if (!array_key_exists('title', $settings)) {
			$settings['title'] = false;
		}
		$settings['fields'] = array();
		$settings['fields'][] = ['fields' => ['name', 'alt_name'], 'distribution' => [8, 4]];
		return $settings;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'alt_name' => 'Alternative Name',
			'created' => 'Created',
			'created_user_id' => 'Created User ID',
			'modified' => 'Modified',
			'modified_user_id' => 'Modified User ID',
			'deleted' => 'Deleted',
			'deleted_user_id' => 'Deleted User ID',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getRegistry()
	{
		return $this->hasOne('Registry', ['id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCreatedUser()
	{
		return $this->hasOne('User', ['id' => 'created_user_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getDeletedUser()
	{
		return $this->hasOne('User', ['id' => 'deleted_user_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getModifiedUser()
	{
		return $this->hasOne('User', ['id' => 'modified_user_id']);
	}
}
