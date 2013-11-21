<?php
/**
 * library/db/ActiveRecord.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package infinite
 */


namespace app\components\db;

use app\components\web\form\Segment as FormSegment;
use app\components\db\fields\Model as ModelField;

trait ActiveRecordTrait {

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
		return new FormSegment($this, $name, $settings);
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
	public static function getFields($model = null, $univeralFieldSettings = null) {
		if (is_null($model)) {
			$model = self::find();
		}
		if (is_null($univeralFieldSettings)) {
			$univeralFieldSettings = array();
		}
		$modelName = get_class($model);
		$fieldKey = md5($modelName .'.'. $model->primaryKey);
		if (empty(self::$_fields[$fieldKey])) {
			self::$_fields[$fieldKey] = array();
			$fieldSettings = $model->fieldSettings();
			foreach (array_merge($model->additionalFields(), $modelName::getTableSchema()->columns) as  $name => $column) {
				$settings = $univeralFieldSettings;
				if (isset($fieldSettings[$name])) {
					$settings = array_merge_recursive($settings, $fieldSettings[$name]);
				}
				self::$_fields[$fieldKey][$name] = new ModelField($model, $name, $settings);
			}
		}
		return self::$_fields[$fieldKey];
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
