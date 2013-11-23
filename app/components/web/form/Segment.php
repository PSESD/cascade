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

	public function init()
	{
		parent::init();

		$this->isValid =  $this->model->setFormValues($this->name);
		if (!empty($this->settings['ignoreInvalid'])) {
			$this->isValid = true;
			$this->model->clearErrors();
		}

		$this->autogenerate($this->settings);
	}

	public function setModel($model)
	{
		$this->_model = $model;
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

	public function setSettings($settings)
	{
		if (is_null($this->model)) {
			throw new Exception("You must set the model before you can set the settings.");
		}
		$this->_settings = $this->model->formSettings($this->name, $settings);
		if (is_null($this->_settings) and !empty($settings)) {
			$this->_settings = $settings;
		}
		if (empty($this->_settings['fields'])) {
			$this->_settings['fields'] = [];
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
	 *
	 * @return unknown
	 */
	public function getName() {
		return $this->_name;
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
	public function generate()
	{
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

		$fields = $this->_model->getFields($this);
		$fieldsTemplate = false;

		if (!isset($this->_settings['fields'])) {
			$fieldTemplate = [];
			foreach ($fields as $fieldName => $field) {
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
				$this->grid->prepend($fields['id']->formField);
			}
			$this->grid->prepend($fields['_moduleHandler']->formField);
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
