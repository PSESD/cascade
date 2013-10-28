<?php
/**
 * ./app/components/web/form/RFormRelationSegment.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\form;

use Yii;

use \cascade\components\objects\fields\Model as ModelField;
use \cascade\models\Relation;
use \cascade\models\Registry;

use \infinite\helpers\Html;
use \infinite\db\behaviors\Relatable;

class RelationSegment extends \infinite\base\Object {
	protected $_relationType;
	protected $_possibleRelatives;
	protected $_parentModel;
	protected $_initialModels;
	protected $_objectType;
	protected $_settings;
	public $isValid = true;


	/**
	 *
	 *
	 * @param unknown $model
	 * @param unknown $type
	 * @param unknown $initialModels (optional)
	 */
	function __construct($model, $type, $initialModels = array(), $additionalSettings = array()) {
		$this->_parentModel = $model;
		$this->_relationType = $type;
		$this->_initialModels = $initialModels;
		if (empty($this->_initialModels)) {
			//$this->_initialModels = array();
		}
		$this->_objectType = Yii::$app->types->refByModel(get_class($model));
		if (!empty($this->_objectType)) {
			switch ($this->_relationType) {
			case 'parents':
				$this->_possibleRelatives = $this->_objectType->getParents();
				$this->_settings = $this->_objectType->module->parentSettings();
				break;
			case 'children':
				$this->_possibleRelatives = $this->_objectType->getChildren();
				$this->_settings = $this->_objectType->module->childrenSettings();
				break;
			}
		}
		if (!empty($additionalSettings['limitModules']) AND !empty($this->_possibleRelatives)) {
			foreach ($this->_possibleRelatives as $k => $r) {
				switch ($this->_relationType) {
					case 'parents':
						if (!in_array($r->parent->shortName, $additionalSettings['limitModules'])) {
							unset($this->_possibleRelatives[$k]);
						}
					break;
					case 'children':
						if (!in_array($r->child->shortName, $additionalSettings['limitModules'])) {
							unset($this->_possibleRelatives[$k]);
						}
					break;
				}
			}
			unset($additionalSettings['limitModules']);
		}
		if (is_array($this->_settings)) {
			$this->_settings = array_merge($this->_settings, $additionalSettings);
		}
	}


	/**
	 *
	 *
	 * @param unknown $model     (optional)
	 * @param unknown $formField (optional)
	 * @return unknown
	 */
	public function get($model = null, $formField = array()) {
		if (empty($this->_possibleRelatives) || $this->_initialModels === false) {
			return '';
		}
		$result = array();
		if (!empty($this->_settings['title'])) {
			$result[] = Html::beginTag('fieldset');
			$result[] = Html::tag('legend', $this->_settings['title']);
		}
		$uniqueId = 'relations-'.md5(uniqid());
		$result[] = '<div id="'.$uniqueId.'" class="relations"></div>';
		$settings = $this->_settings;
		unset($settings['title']);
		$settings['type'] = $this->_relationType;
		$settings['possibleRelatives'] = array();
		foreach ($this->_possibleRelatives as $r) {
			$p = $r->options;
			switch ($this->_relationType) {
			case 'parents':
				$p['type'] = $r->parent->shortName;
				$p['label'] = $r->parent->title->getSingular(true);
				break;
			case 'children':
				$p['type'] = $r->child->shortName;
				$p['label'] = $r->child->title->getSingular(true);
				break;
			}
			if (empty($p['type'])) { continue; }
			if (isset($p['taxonomy'])) {
				$t = array('taxonomy' => $p['taxonomy']);
				$taxonomy = Yii::$app->taxonomyEngine->get($p['taxonomy']);
				$t['label'] = $taxonomy->name;
				$t['multiple'] = $taxonomy->multiple;
				$t['required'] = $taxonomy->required;
				if (!empty($taxonomy->default)) {
					$t['default'] = array();
					if (!is_array($taxonomy->default)) {
						$taxonomy->default = array($taxonomy->default);
					}
					foreach ($taxonomy->default as $k => $v) {
						$tt = $taxonomy->getTaxonomy($v);
						if (!empty($tt)) {
							$t['default'][] = $tt->id;
						}
					}
				}
				$t['options'] = $taxonomy->getTaxonomyList();
				$p['taxonomy'] = $t;
			}
			$settings['possibleRelatives'][$p['type']] = $p;
		}
		//$uniqueBase = substr(md5(uniqid()), 0, 10);
		$settings['fieldNameTemplate'] = Relatable::RELATION_MODEL.'[{uniq}][{field}]';

		$settings['baseRelation'] = array();
		$settings['baseRelation']['attributes'] = array();
		$baseModel = Relatable::RELATION_MODEL;
		$baseModel = new $baseModel;
		foreach (array_merge($baseModel->attributes, $baseModel->additionalAttributes) as $k => $v) {
			if ($k === 'active') {
				$v = 1;
			}
			if ($k === 'taxonomy_ids') {
				$v = null;
			}
			$field = new ModelField($baseModel, $k, array());
			$attr = $field->formField->getModelField(array());
			$settings['baseRelation']['attributes'][$k] = array('field' => $k, 'label' => $baseModel->getAttributeLabel($k), 'value' => $v);
		}

		$settings['initial'] = array();
		if ($this->_initialModels) {
			foreach ($this->_initialModels as $key => $i) {
				$objectId = null;
				switch ($this->_relationType) {
				case 'parents':
					$objectId = $i['model']->parent_object_id;
					break;
				case 'children':
					$objectId = $i['model']->child_object_id;
					break;
				}
				if (!empty($objectId)) {
					if (is_object($objectId)) {
						$object = $objectId;
					} else {
						$object = Registry::getObject($objectId);
					}
				}
				
				if (empty($object)) {
					continue;
				}
				$p = array();
				$p['key'] = $key;
				$sm = array();
				$sm['tabular'] = array($p['key']);
				$p['module'] = $object->typeModule->shortName;
				$p['descriptor'] = $object->descriptor;
				$p['errors'] = $i['model']->errors;
				$p['attributes'] = array();
				foreach (array_merge($i['model']->attributes, $i['model']->additionalAttributes) as $k => $v) {
					$field = new ModelField($i['model'], $k, array());
					$attr = $field->formField->getModelField($sm);
					$p['attributes'][$k] = array('field' => $k, 'name' => RHtml::resolveName($i['model'], $attr), 'value' => $v);
				}
				foreach (array('parent_object_id', 'child_object_id') as $pkey) {
					if (is_object($p['attributes'][$pkey])) {
						unset($p['attributes'][$pkey]);
					}
				}
				$settings['initial'][] = $p;
			}
		}
		//RDebug::d(json_decode(json_encode($settings)));
		$settingsJson = json_encode($settings);
		$script = 'setTimeout(function(){ $("#'. $uniqueId .'").relationBuilder('.$settingsJson.'); }, 300);';
		//$script = "var setts = $settingsJson; setTimeout(function(){ console.log(setts); }, 300);";
		if (Yii::$app->request->isAjaxRequest) {
			$result[] = Html::script($script);
		} else {
			Html::onLoadBlock($script);
		}
		if (!empty($this->_settings['title'])) {
			$result[] = Html::endTag('fieldset');
		}
		return implode("\n", $result);
	}


	/**
	 *
	 *
	 * @param unknown $model     (optional)
	 * @param unknown $formField (optional)
	 */
	public function render($model = null, $formField = array()) {
		echo $this->get($model, $formField);
	}


}


?>
