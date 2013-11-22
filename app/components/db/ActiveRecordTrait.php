<?php
/**
 * library/db/ActiveRecord.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package infinite
 */


namespace app\components\db;

use Yii;

use app\components\web\form\Segment as FormSegment;

trait ActiveRecordTrait {
	public $modelFieldClass = 'app\components\db\fields\Model';
	public $relationFieldClass = 'app\components\db\fields\Relation';

	static protected $_fields = array();
	protected $_defaultOrder = '{alias}.name ASC';

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

	public function getDefaultOrder($alias = 't') {
		if (is_string($this->_defaultOrder)) {
			return strtr($this->_defaultOrder, array('{alias}' => $alias));
		} else {
			return $this->_defaultOrder;
		}
	}

	/**
	 *
	 *
	 * @param unknown $name
	 * @param unknown $settings (optional)
	 * @return unknown
	 */
	public function form($name, $settings = array()) {
		Yii::beginProfile(__CLASS__ .':'. __FUNCTION__);
		$form = new FormSegment($this, $name, $settings);
		Yii::endProfile(__CLASS__ .':'. __FUNCTION__);
		return $form;
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function additionalFields() {
		return array();
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getAdditionalAttributes() {
		$add = array();
		$af = $this->additionalFields();
		foreach (array_keys($af) as $field) {
			$add[$field] = $this->{$field};
		}
		return $add;
	}


	/**
	 *
	 *
	 * @param unknown $model                 (optional)
	 * @param unknown $univeralFieldSettings (optional)
	 * @return unknown
	 */
	public static function getFields() {
		$modelName = static::className();
		$model = new $modelName;
		$fieldKey = md5($modelName .'.'. $model->primaryKey);
		if (empty(self::$_fields[$fieldKey])) {
			self::$_fields[$fieldKey] = array();
			$fieldSettings = $model->fieldSettings();
			foreach (array_merge($model->additionalFields(), $modelName::getTableSchema()->columns) as  $name => $column) {
				$settings = [];
				if (isset($fieldSettings[$name])) {
					$settings = array_merge_recursive($settings, $fieldSettings[$name]);
				}
				$settings['class'] = $model->modelFieldClass;
				$settings['model'] = $model;
				$settings['field'] = $name;

				self::$_fields[$fieldKey][$name] = Yii::createObject($settings);
			}
		}
		$objectTypeItem = $model->objectTypeItem;
		if ($objectTypeItem) {
			foreach ($objectTypeItem->parents as $relationship) {
				$fieldName = 'parent:'. $relationship->parent->systemId;
				$settings = [];
				if (isset($fieldSettings[$fieldName])) {
					$settings = array_merge_recursive($settings, $fieldSettings[$fieldName]);
				}
				$settings['class'] = $model->relationFieldClass;
				$settings['model'] = $model;
				$settings['field'] = $fieldName;
				$settings['relationship'] = $relationship;
				$settings['modelRole'] = 'child';

				self::$_fields[$fieldKey][$fieldName] = Yii::createObject($settings);
			}
			foreach ($objectTypeItem->children as $relationship) {
				$fieldName = 'child:'. $relationship->child->systemId;
				$settings = [];
				if (isset($fieldSettings[$fieldName])) {
					$settings = array_merge_recursive($settings, $fieldSettings[$fieldName]);
				}
				$settings['class'] = $model->relationFieldClass;
				$settings['model'] = $model;
				$settings['field'] = $fieldName;
				$settings['relationship'] = $relationship;
				$settings['modelRole'] = 'parent';

				self::$_fields[$fieldKey][$fieldName] = Yii::createObject($settings);
			}
		}
		return self::$_fields[$fieldKey];
	}

	public function getObjectType() {
		$objectTypeItem = $this->objectTypeItem;
		if ($objectTypeItem) {
			return $objectTypeItem->object;
		}
		return false;
	}

	public function getObjectTypeItem() {
		if (Yii::$app->collectors['types']->has(get_class($this), 'object.primaryModel')) {
			return Yii::$app->collectors['types']->getOne(get_class($this), 'object.primaryModel');
		}
		return false;
	}
	/**
	 *
	 *
	 * @return unknown
	 */
	public function fieldSettings() {
		return null;
	}


	public function formSettings($name, $settings = [])
	{
		return null;
	}

	/**
	 *
	 *
	 * @param unknown $key (optional)
	 * @return unknown
	 */
	public function setFormValues($key = null) {
		if (!isset($_POST[get_class($this)])) { return true; }
		$base = $_POST[get_class($this)];
		if (is_null($key) or $key === 'primary') {
			if (!empty($base)) {
				$this->attributes = $base;
			}
		} else {
			$key = md5($key);
			if (!empty($base[$key])) {
				$this->attributes = $base[$key];
			}
		}

		return $this->validate();
	}
}


?>
