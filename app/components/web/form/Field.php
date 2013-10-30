<?php
/**
 * ./app/components/web/form/RFormField.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\form;

use \infinite\helpers\Html;

use \yii\helpers\Json;

class Field extends \infinite\base\Object {
	public $modelField;
	public $options;
	public $htmlOptions;
	public $default;
	public $label;
	public $required; // for selectors
	public $showLabel = true;
	public $showError = true;

	protected $_type;

	protected $_model;

	/**
	 *
	 *
	 * @param unknown $modelField
	 * @param unknown $settings
	 * @return unknown
	 */
	public function __construct($modelField, $settings) {
		$this->modelField = $modelField;
		foreach ($settings as $k => $v) {
			$this->{$k} = $v;
		}
		return true;
	}



	/**
	 *
	 *
	 * @param unknown $model        (optional)
	 * @param unknown $formSettings (optional)
	 */
	public function render($model = null, $formSettings = array()) {
		echo $this->get($model, $formSettings);
	}


	/**
	 *
	 *
	 * @param unknown $formSettings (optional)
	 * @return unknown
	 */
	public function getModelField($formSettings = array()) {
		$field = $this->field;
		if (isset($formSettings['tabular'])) {
			if (is_string($formSettings['tabular'])) {
				$field = '['. $formSettings['tabular'] .']' . $field;
			} else {
				foreach (array_reverse($formSettings['tabular']) as $t) {
					$field = '['. $t .']' . $field;
				}
			}
		}
		return $field;
	}



	/**
	 *
	 *
	 * @param unknown $model        (optional)
	 * @param unknown $formSettings (optional)
	 * @return unknown
	 */
	public function get($model = null, $formSettings = array()) {
		if (is_null($model)) {
			$model = $this->model;
		}
		$pre = $post = null;
		$field = $this->getModelField($formSettings);
		switch ($this->type) {
		case 'checkBox':
			$item = Html::activeCheckBox($model, $field, $this->htmlOptions);
			break;
		case 'radioButton':
			$item = Html::activeRadioButton($model, $field, $this->htmlOptions);
			break;
		case 'checkBoxList':
			$item = Html::activeCheckBoxList($model, $field, $this->options, $this->htmlOptions);
			break;
		case 'radioButtonList':
			$item = Html::activeRadioBoxList($model, $field, $this->options, $this->htmlOptions);
			break;
		case 'dropDownList':
			$item = Html::activeDropDownList($model, $field, $this->options, $this->htmlOptions);
			break;
		case 'listBox':
			$item = Html::activeListBox($model, $field, $this->options, $this->htmlOptions);
			break;
		case 'file':
			$item = Html::activeRadioButton($model, $field, $this->htmlOptions);
			break;
		case 'hidden':
			$this->showLabel = false;
			$item = Html::activeHiddenField($model, $field, $this->htmlOptions);
			break;
		case 'password':
			$item = Html::activePasswordField($model, $field, $this->htmlOptions);
			break;
		case 'text':
			$item = Html::activeTextField($model, $field, $this->htmlOptions);
			break;
		case 'date':
			if (!isset($this->htmlOptions['class'])) {
				$this->htmlOptions['class'] = '';
			}
			$this->htmlOptions['class'] .= ' date';
			$item = Html::activeTextField($model, $field, $this->htmlOptions);
			break;
		case 'objectMatch':
			$this->htmlOptions['class'] = 'selector input-default-text';
			if ($this->required) {
				$this->htmlOptions['data-default-text'] = '(choose)';
			} else {
				$this->htmlOptions['data-default-text'] = '(none)';
			}
			$this->htmlOptions['maxlength'] = false;
			$item = Html::activeAutocompleteField($model, $field, $this->htmlOptions);
			break;
		case 'textArea':
			$item = Html::activeTextArea($model, $field, $this->htmlOptions);
			break;
		case 'rich':
			if (!isset($this->htmlOptions['class'])) {
				$this->htmlOptions['class'] = '';
			}
			$this->htmlOptions['class'] .= ' rich';
			$editorSettings = array(
				);
			$this->htmlOptions['data-editor'] = Json::encode($editorSettings);
			$item = Html::activeTextArea($model, $field, $this->htmlOptions);
			break;
		}
		if ($this->showLabel) {
			$labelSettings = array();
			if (!is_null($this->label)) {
				$labelSettings['label'] = $this->label;
			}
			if (!is_null($this->required)) {
				$labelSettings['required'] = $this->required;
			}
			$pre = Html::activeLabel($model, $field, $labelSettings);
		}
		if ($this->showError) {
			$post = Html::error($model, $field);
		}
		if (!empty($item)) {
			return $pre.$item.$post;
		}
		return false;
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
