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
	public function generate() {
		$model = $this->model;
		if (!$this->generator || !$this->generator->form) {
			throw new Exception("Unable to find generator form.");
		}
		$form = $this->generator->form;
		$pre = $post = null;
		$field = $this->getModelField();

		$fieldConfig = [
				'template' => "<div class=\"\">{input}</div>\n<div class=\"\">{error}</div>",
				'labelOptions' => ['class' => "control-label"],
		];
		if ($this->showLabel) {
			$fieldConfig['template'] = "{label}\n".$fieldConfig['template'];
		}
		$item = $form->field($model, $field, $fieldConfig);
		if (!isset($this->htmlOptions['class'])) {
			$this->htmlOptions['class'] = '';
		}
		$this->htmlOptions['class'] .= ' form-control';
		switch ($this->type) {
		case 'checkBox':
			$item->checkbox();
			break;
		case 'radioButton':
			$item->radio();
			break;
		case 'checkBoxList':
			$item->checkboxList($this->options);
			break;
		case 'radioButtonList':
			$item->radioList($this->options);
			break;
		case 'dropDownList':
			$item->dropDownList($this->options);
			break;
		case 'listBox':
			$item->listBox($this->options);
			break;
		case 'file':
			$item->fileInput();
			break;
		case 'hidden':
			$this->showLabel = false;
			$item = Html::activeHiddenField($model, $field, $this->htmlOptions);
			break;
		case 'password':
			$item->password();
			break;
		case 'date':
			$this->htmlOptions['class'] .= ' date';
			break;
		case 'textarea':
			$item->textarea();
			break;
		case 'rich':
			$this->htmlOptions['class'] .= ' rich';
			$editorSettings = array(
				);
			$this->htmlOptions['data-editor'] = Json::encode($editorSettings);
			$item = Html::activeTextArea($model, $field, $this->htmlOptions);
			break;
		}
		if ($item instanceof ActiveField) {
			$item->inputOptions = $this->htmlOptions;
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
