<?php

namespace app\modules\TypeIndividual\models;

/**
 * This is the model class for table "object_individual".
 *
 * @property string $id
 * @property string $user_id
 * @property string $prefix
 * @property string $suffix
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $title
 * @property string $department
 * @property string $birthday
 * @property string $created
 * @property string $created_user_id
 * @property string $modified
 * @property string $modified_user_id
 * @property string $deleted
 * @property string $deleted_user_id
 *
 * @property User $createdUser
 * @property User $deletedUser
 * @property User $modifiedUser
 * @property Registry $registry
 * @property User $user
 * @property User[] $users
 */
class ObjectIndividual extends \app\components\db\ActiveRecord
{
	use \app\components\types\ActiveRecordTrait;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_individual';
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
			[['first_name'], 'required'],
			[['birthday'], 'safe'],
			//[['created', 'modified', 'deleted'], 'unsafe'],
			[['id', 'user_id', 'created_user_id', 'modified_user_id', 'deleted_user_id'], 'string', 'max' => 36],
			[['prefix', 'suffix', 'first_name', 'middle_name', 'last_name', 'title', 'department'], 'string', 'max' => 255]
		];
	}


	/**
	 * @inheritdoc
	 */
	public function fieldSettings()
	{
		return [
			'prefix' => [],
			'suffix' => [],
			'first_name' => [],
			'middle_name' => [],
			'last_name' => [],
			'title' => [],
			'department' => [],
			'birthday' => []
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
		$settings['fields'][] = ['fields' => ['first_name', 'middle_name', 'last_name'], 'distribution' => null];
		$settings['fields'][] = ['fields' => ['title', 'department'], 'distribution' => null];
		if (!$this->isNewRecord) {
			$settings['fields'][] = ['fields' => ['birthday', false, false]];
		}
		return $settings;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'prefix' => 'Prefix',
			'suffix' => 'Suffix',
			'first_name' => 'First Name',
			'middle_name' => 'Middle Name',
			'last_name' => 'Last Name',
			'title' => 'Title',
			'department' => 'Department',
			'birthday' => 'Birthday',
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
	public function getUser()
	{
		return $this->hasOne('User', ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUsers()
	{
		return $this->hasMany('User', ['object_individual_id' => 'id']);
	}
}
