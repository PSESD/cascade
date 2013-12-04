<?php
namespace app\modules\TypePostalAddress\models;

use app\models\Registry;
use infinite\helpers\Locations;

/**
 * This is the model class for table "object_postal_address".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $country
 * @property boolean $no_mailings
 * @property string $created
 * @property string $modified
 *
 * @property Registry $registry
 */
class ObjectPostalAddress extends \app\components\types\ActiveRecord
{
	public $descriptorField = 'name';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object_postal_address';
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
			[['type'], 'string'],
			[['no_mailings'], 'boolean'],
			[['id'], 'string', 'max' => 36],
			[['name', 'address1', 'address2', 'city', 'country'], 'string', 'max' => 255],
			[['state'], 'string', 'max' => 100],
			[['postal_code'], 'string', 'max' => 20]
		];
	}


	/**
	 * @inheritdoc
	 */
	public function fieldSettings()
	{
		return [
			'name' => [],
			'type' => [],
			'address1' => [],
			'address2' => [],
			'city' => [],
			'state' => [],
			'postal_code' => [],
			'country' => [
				'default' => 'US',
				'formField' => array('type' => 'dropDownList', 'options' => Locations::countryList()),
			],
			'no_mailings' => []
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
		$settings['fields'][] = ['name'];
		$settings['fields'][] = ['address1', 'address2'];
		$settings['fields'][] = ['city', 'state', 'postal_code'];
		$settings['fields'][] = ['country', false, false];
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
			'type' => 'Type',
			'address1' => 'Address (Line 1)',
			'address2' => 'Address (Line 2)',
			'city' => 'City',
			'state' => 'State/Province/Region',
			'postal_code' => 'Postal Code',
			'country' => 'Country',
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
		return $this->hasOne(Registry::className(), ['id' => 'id']);
	}
}
