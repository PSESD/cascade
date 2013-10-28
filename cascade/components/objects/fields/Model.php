<?php
/**
 * ./app/components/objects/fields/Model.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\objects\fields;

use HumanFieldDetector;
use FormatText;
use BaseFormat;

use \cascade\components\web\form\Field as FormField;

class Model extends \infinite\base\Component {
	public $field;
	public $default;
	protected $_human;
	protected $_format;
	protected $_label;
	protected $_model;
	protected $_formField;

	/**
	 *
	 *
	 * @param unknown $model
	 * @param unknown $field
	 * @param unknown $settings
	 * @return unknown
	 */
	public function __construct($model, $field, $settings) {
		$this->model = $model;
		$this->field = $field;
		foreach ($settings as $k => $v) {
			$this->{$k} = $v;
		}
		if (!is_null($this->default) and $model->isDefaultValue($field)) {
			$model->{$field} = $this->default;
		}
		return true;
	}


	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setHuman($value) {
		$this->_human = $value;
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getHuman() {
		if (is_null($this->_human)) {
			$this->_human = HumanFieldDetector::test($this->field);
		}
		return $this->_human;
	}


	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setFormField($value) {
		if (is_array($value)) {
			$value = new FormField($this, $value);
		}

		$this->_formField = $value;
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getFormField() {
		if (is_null($this->_formField)) {
			$this->_formField = new FormField($this, array());
		}
		return $this->_formField;
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getMetaData() {
		if (!$this->model) {
			return false;
		}
		
		if (!isset($this->model->metaData->columns[$this->field])) {
			return false;
		}

		return $this->model->metaData->columns[$this->field];
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getModel() {
		if (is_null($this->_model)) {
			return false;
		}
		if (!is_object($this->_model)) {
			$this->_model = new $this->_model;
		}
		return $this->_model;
	}


	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setModel($value) {
		$this->_model = $value;
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getFormat() {
		if (is_null($this->_format)) {
			return $this->_format = new FormatText($this);
		}
		return $this->_format;
	}


	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setFormat($value) {
		if (is_array($value)) {
			$this->_format = Yii::createObject($value, $this);
		}
		$this->_format = $value;
		return true;
	}

	public function getFormattedValue() {
		if ($this->format instanceof BaseFormat) {
			return $this->format->get();
		} elseif (is_callable($this->format) OR (is_array($this->format) AND !empty($this->format[0]) AND is_object($this->format[0]))) {
			return $this->evaluateExpression($this->format, array($this->value));
		} else {
			return $this->value;
		}
	}

	public function getValue() {
		return $this->model->{$this->field};
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getLabel() {
		if (is_null($this->_label)) {
			$this->_label = $this->getModel()->getAttributeLabel($this->field);
		}
		return $this->_label;
	}


	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setLabel($value) {
		$this->_label = $value;
		return true;
	}


}


?>
