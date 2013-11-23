<?php
namespace app\components\web\form\fields;

use \infinite\base\exceptions\Exception;
use \infinite\helpers\Html;

class Model extends Base {
	/**
	 *
	 *
	 * @param unknown $model        (optional)
	 * @param unknown $formSettings (optional)
	 * @return unknown
	 */
	public function generate() {
		$model = $this->model;
		if (!$this->generator ) {
			throw new Exception("Unable to find generator.");
		}
		if (!$this->generator->form) {
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
			$item = Html::activeHiddenInput($model, $field, $this->htmlOptions);
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
}
?>