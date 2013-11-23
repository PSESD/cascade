<?php
/**
 * ./app/components/web/form/RFormField.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\form\fields;

use \infinite\helpers\Html;
use \infinite\base\exceptions\Exception;

use \yii\helpers\Json;

abstract class Base extends \infinite\base\Object implements \infinite\web\grid\CellContentInterface {
	use \app\components\web\form\FormObjectTrait;
	use \infinite\web\grid\CellContentTrait;

	public $modelField;
	public $options;
	public $htmlOptions = [];
	public $default;
	public $label;
	public $required; // for selectors
	public $showLabel = true;
	public $showError = true;

	protected $_type;
	protected $_model;

	abstract public function generate();

	/**
	 *
	 *
	 * @param unknown $formSettings (optional)
	 * @return unknown
	 */
	public function getModelField($formSettings = array()) {

		return "{$this->model->tabularPrefix}{$this->field}";
	}



	/**
	 *
	 *
	 * @return unknown
	 */
	public function getType() {
		if (is_null($this->_type)) {
			if (!$this->modelField->human) {
				$this->_type = 'hidden';
			} else {
				$fieldType = $type = 'text';
				if (isset($this->modelField->metaData->dbType)) {
					$fieldType = $this->modelField->metaData->dbType;
				}
				switch ($fieldType) {
					case 'date':
						$type = $fieldType;
					break;
				}
				$this->_type = $type;
			}
		}
		return $this->_type;
	}


	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setType($value) {
		$this->_type = $value;
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getModel() {
		if (is_null($this->modelField)) {
			return false;
		}
		return $this->modelField->model;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getField() {
		if (is_null($this->modelField)) {
			return false;
		}
		return $this->modelField->field;
	}


}


?>
