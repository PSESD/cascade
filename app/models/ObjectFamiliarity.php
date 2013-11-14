<?php

namespace app\models;

/**
 * This is the model class for table "object_familiarity".
 *
 * @property string $object_id
 * @property string $user_id
 * @property string $model
 * @property boolean $watching
 * @property boolean $created
 * @property integer $modified
 * @property integer $accessed
 * @property integer $familiarity
 * @property string $session
 * @property string $last_modified
 * @property string $last_accessed
 * @property string $first_accessed
 *
 * @property User $user
 * @property Registry $object
 */
class ObjectFamiliarity extends \app\components\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_familiarity';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['object_id', 'user_id', 'familiarity'], 'required'],
			[['watching', 'created'], 'boolean'],
			[['modified', 'accessed', 'familiarity'], 'integer'],
			[['last_modified', 'last_accessed', 'first_accessed'], 'safe'],
			[['object_id', 'user_id'], 'string', 'max' => 36],
			[['model'], 'string', 'max' => 255],
			[['session'], 'string', 'max' => 32]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'object_id' => 'Object ID',
			'user_id' => 'User ID',
			'model' => 'Model',
			'watching' => 'Watching',
			'created' => 'Created',
			'modified' => 'Modified',
			'accessed' => 'Accessed',
			'familiarity' => 'Familiarity',
			'session' => 'Session',
			'last_modified' => 'Last Modified',
			'last_accessed' => 'Last Accessed',
			'first_accessed' => 'First Accessed',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getObject()
	{
		return $this->hasOne(Registry::className(), ['id' => 'object_id']);
	}
}
