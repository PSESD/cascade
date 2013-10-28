<?php
/**
 * ./app/components/web/form/RFormModel.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\form;

use Field;
use Segment;

class Model extends \yii\base\Model {
	static $_fields = array();

	/**
	 *
	 *
	 * @param unknown $model                 (optional)
	 * @param unknown $univeralFieldSettings (optional)
	 * @return unknown
	 */
	public static function getFields($model = null, $univeralFieldSettings = null) {
		if (is_null($model)) {
			$model = self::model();
		}
		if (is_null($univeralFieldSettings)) {
			$univeralFieldSettings = array();
		}
		$modelName = get_class($model);
		if (empty(self::$_fields[$modelName])) {
			self::$_fields[$modelName] = array();
			$fieldSettings = $model->fieldSettings();
			foreach ($model->getProperties() as $name) {
				$settings = $univeralFieldSettings;
				if (isset($fieldSettings[$name])) {
					$settings = array_merge_recursive($settings, $fieldSettings[$name]);
				}
				self::$_fields[$modelName][$name] = new Field($model, $name, $settings);
			}
		}
		return self::$_fields[$modelName];
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getProperties() {
		$properties = array();
		$class = new ReflectionClass(get_class($this));
		foreach ($class->getProperties() as $property) {
			$name=$property->getName();
			if ($property->isPublic() && !$property->isStatic()) {
				$properties[] = $name;
			}
		}
		return $properties;
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


	/**
	 *
	 *
	 * @return unknown
	 */
	public function fieldSettings() {
		return null;
	}


	/**
	 *
	 *
	 * @param unknown $name
	 * @param unknown $settings (optional)
	 * @return unknown
	 */
	public function formSettings($name, $settings = array()) {
		return null;
	}


	/**
	 *
	 *
	 * @param unknown $name
	 * @param unknown $settings (optional)
	 * @return unknown
	 */
	public function form($name, $settings = array()) {
		return new Segment($this, $name, $settings);
	}


}


?>
