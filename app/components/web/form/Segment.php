<?php
/**
 * ./app/components/web/form/RFormSegment.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\form;

use Yii;

use \app\components\db\fields\Model as ModelField;

use \infinite\web\grid\Grid;
use \infinite\helpers\Html;

class Segment extends FormObject {
	public $cellClass = '\app\components\web\form\fields\Cell';

	protected $_name;
	protected $_model;
	protected $_settings;
	protected $_grid;


	/**
	 *
	 *
	 * @param unknown $model
	 * @param unknown $name
	 * @param unknown $settings (optional)
	 */
	function __construct($model, $name, $settings = array()) {
		$this->_model = $model;
		$this->_name = $name;
		$this->isValid =  $this->_model->setFormValues($name);
		if (!empty($settings['ignoreInvalid'])) {
			$this->isValid = true;
			$this->_model->clearErrors();
		}
		$this->_settings = $model->formSettings($name, $settings);
		if (is_null($this->_settings) and !empty($settings)) {
			$this->_settings = $settings;
		}
		$this->autogenerate($this->_settings);
		if (empty($this->_settings['fields'])) {
			$this->_settings['fields'] = array();
		}
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getModel() {
		return $this->_model;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSettings() {
		return $this->_settings;
	}


	/**
	 *
	 */
	public function render() {
		echo $this->generate();
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function generate() {
		$result = [];
		if (!empty($this->_settings['title'])) {
			$result[] = Html::beginTag('fieldset');
			$result[] = Html::tag('legend', $this->_settings['title']);
		}

		$result[] = $this->grid->generate();

		if (!empty($this->_settings['title'])) {
			$result[] = Html::endTag('fieldset');
		}
		return implode("\n", $result);
	}


	/**
	 *
	 *
	 * @param unknown $settings
	 * @return unknown
	 */
	protected function autogenerate($settings) {
		if (is_array($settings)) {
			$this->_settings = $settings;
		} else {
			$this->_settings = array();
		}
		if (!isset($this->_settings['fieldSettings'])) {
			$this->_settings['fieldSettings'] = array();
		}
		if (!isset($this->_settings['formField'])) {
			$this->_settings['formField'] = array();
		}

		$modelClass = get_class($this->_model);
		$fields = $modelClass::getFields($this->_model, $this->_settings['fieldSettings']);
		$fieldsTemplate = false;

		if (!isset($this->_settings['fields'])) {
			$fieldTemplate = [];
			foreach ($fields as $fieldName => $field) {
				$field->formField->owner = $this;
				if (!$field->human) { continue; }
				if (!($field instanceof ModelField)) { continue; }
				$fieldsTemplate[] = [$fieldName];
			}
		} else {
			$fieldsTemplate = $this->_settings['fields'];
		}

		if ($fieldsTemplate !== false) {
			$this->_settings['fields'] = array();
			if (!$this->_model->isNewRecord) {
				$this->grid->prependContent($fields['id']->formField);
			}
			$cellClass = $this->cellClass;
			foreach ($fieldsTemplate as $rowFields) {
				$rowItems = [];
				foreach ($rowFields as $fieldKey => $fieldSettings) {
					if (is_numeric($fieldKey)) {
						$fieldKey = $fieldSettings;
						$fieldSettings = [];
					}
					
					if ($fieldKey === false || $fieldKey === ':empty') {
						$rowItems[] = Yii::createObject(['class' => $cellClass, 'content' => '&nbsp;']);
						continue;
					}

					if ($fieldKey === ':separator') {
						$rowItems[] = Yii::createObject(['class' => $cellClass, 'content' => '<span class="separator"></span>']);
						continue;
					}

					if (!isset($fields[$fieldKey])) { continue; }

					if ($fieldKey === false) {
						$rowItems[] = false;
					} else {
						$fields[$fieldKey]->formField->owner = $this;
						$rowItems[] = Yii::createObject(['class' => $cellClass, 'content' => $fields[$fieldKey]->formField->configure($fieldSettings)]);
					}
				}
				$this->grid->addRow($rowItems);
			}
		}
		return true;
	}

	public function getGrid()
	{
		if (is_null($this->_grid)) {
			$this->_grid = new Grid;
		}
		return $this->_grid;
	}

}


?>
