<?php
/**
 * ./app/components/web/form/RFormSegment.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\form;

use RelationSegment;
use Row;

use \cascade\components\objects\fields\Model as ModelField;

use \infinite\helpers\Html;

class Segment extends \infinite\base\Object {
	protected $_name;
	protected $_model;
	protected $_settings;
	public $isValid = true;


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
		echo $this->get();
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function get() {
		$result = '';
		if (isset($this->_settings['preSegments'])) {
			foreach ($this->_settings['preSegments'] as $segment) {
				$result .= $segment->get();
			}
		}
		
		if (!empty($this->_settings['title'])) {
			$result .= Html::beginTag('fieldset');
			$result .= Html::tag('legend', $this->_settings['title']);
		}
		if (!isset($this->_settings['formField'])) {
			$this->_settings['formField'] = array();
		}

		if (isset($this->_settings['fields'])) {
			foreach ($this->_settings['fields'] as $field) {
				$result .= $field->get(null, $this->_settings['formField']);
			}
		}
		if (!empty($this->_settings['title'])) {
			$result .= Html::endTag('fieldset');
		}
		if (isset($this->_settings['postSegments'])) {
			foreach ($this->_settings['postSegments'] as $segment) {
				$result .= $segment->get();
			}
		}
		return $result;
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
		if (!isset($this->_settings['formField']['tabular'])) {
			$this->_settings['formField']['tabular'] = array();
		}

		if (isset($this->_settings['preSegments'])) {
			$this->_settings['preSegments'] = array();
		}
		if (isset($this->_settings['postSegments'])) {
			$this->_settings['postSegments'] = array();
		}

		if (isset($this->_settings['parents'])) {
			$this->_settings['preSegments']['parents'] = new RelationSegment($this->_model, 'parents', $this->_settings['parents']);
		}

		if (isset($this->_settings['children'])) {
			$this->_settings['postSegments']['children'] = new RelationSegment($this->_model, 'children', $this->_settings['children']);
		}

		if (isset($this->_settings['childModels'])) {
			foreach ($this->_settings['childModels'] as $k => $sm) {
				if (!isset($sm['class'])) {
					$sm['class'] = 'Relation';
				}
				$sm['parent'] = $this;
				if (!isset($sm['fieldSettings'])) {
					$sm['fieldSettings'] = array();
				}
				if (!isset($sm['formField'])) {
					$sm['formField'] = array();
				}
				if (!isset($sm['formField']['tabular'])) {
					$sm['formField']['tabular'] = array(md5($k));
				}
				if (!isset($sm['model'])) {
					$sm['model'] = new $sm['class'];
				}
				$this->_settings['postSegments'][$k] = new Segment($sm['model'], $k, $sm);
				if (empty($sm['ignoreInvalid']) and !$this->_settings['postSegments'][$k]->isValid) {
					$this->isValid = false;
				} else {
					$sm['model']->clearErrors();
				}
			}
		}

		$modelClass = get_class($this->_model);
		$fields = $modelClass::getFields($this->_model, $this->_settings['fieldSettings']);
		if (!isset($this->_settings['fields']) and !(is_array($this->_settings) and array_key_exists('fields', $this->_settings) and $this->_settings['fields'] === false)) {
			$this->_settings['fields'] = array();
			if (!$this->_model->isNewRecord) {
				$this->_settings['fields'][] = $fields['id']->formField;
			}
			foreach ($fields as $field) {
				if (!$field->human) { continue; }
				$this->_settings['fields'][] = new Row($field->formField);
			}
		}
		if (isset($this->_settings['matchFields'])) {
			if (!isset($this->_settings['fields'])) {
				$this->_settings['fields'] = array();
			}
			$matchFields = array();
			foreach ($this->_settings['matchFields'] as $k => $sm) {
				$field = new ModelField($this->_model, $k, $sm);
				$matchFields[] = $field->formField;
			}
			$this->_settings['fields'][] = new Row($matchFields);
		}
		return true;
	}


}


?>
