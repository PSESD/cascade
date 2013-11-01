<?php
/**
 * ./app/components/objects/RObjectType.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\types;

use Yii;

use \infinite\base\exceptions\Exception;
use \infinite\helpers\ArrayHelper;

class Type extends \infinite\base\Object {
	// @todo why are these private?
	private $_name;
	private $_module;
	private $_checked;
	private $_children = [];
	private $_parents = [];
	protected $_sections;

	/**
	 *
	 *
	 * @param unknown $name
	 */
	public function __construct($name) {
		$this->_name = $name;
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
	 *
	 * @param unknown $module
	 * @return unknown
	 */
	public function setModule($module) {
		$this->_module = $module;
		if (is_null($module)) { return true; }
		foreach ($this->_module->children() as $key => $child) {
			$options = array('is_string' => true);
			if (is_string($key)) {
				$options = $child;
				$child = $key;
			}
			Yii::$app->types->addRelationship($this->_name, $child, $options);
		}
		foreach ($this->_module->parents() as $key => $parent) {
			$options = array();
			if (is_string($key)) {
				$options = $parent;
				$parent = $key;
			}

			Yii::$app->types->addRelationship($parent, $this->_name, $options);
		}
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getModule() {
		if (is_null($this->_module)) {
			return false;
		}
		return $this->_module;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSections() {
		if (!is_null($this->_sections)) {
			return $this->_sections;
		}
		$this->_sections = array();
		foreach ($this->_children as $rel) {
			if (!$rel->active) { continue; }
			$child = $rel->child;
			$instanceSettings = array('relationship' => $rel, 'whoAmI' => 'parent');
			$items = Yii::$app->widgetEngine->getLocation('parent_objects', $child);
			foreach ($items as $item) {
				$section = $item->getSection($this->module, $instanceSettings);
				if (empty($section)) { continue; }
				if (!isset($this->_sections[$item->section->shortName])) {
					$this->_sections[$section->shortName] = $section;
				}
				$this->_sections[$section->shortName]->addItem($this->module, $item, array('instanceSettings' => $instanceSettings));
			}
		}

		foreach ($this->_parents as $rel) {
			if (!$rel->active) { continue; }
			$parent = $rel->parent;
			$instanceSettings = array('relationship' => $rel, 'whoAmI' => 'child');
			$items = Yii::$app->widgetEngine->getLocation('child_objects', $parent);
			foreach ($items as $item) {
				$section = $item->getSection($this->module, $instanceSettings);
				if (empty($section)) { continue; }
				if (!isset($this->_sections[$item->section->shortName])) {
					$this->_sections[$section->shortName] = $section;
				}
				$this->_sections[$section->shortName]->addItem($this->module, $item, array('instanceSettings' => $instanceSettings));
			}
		}
		//RDebug::d(array_keys($this->_sections));exit;

		ArrayHelper::multisort($this->_sections, 'displayPriority');
		return $this->_sections;
	}


	/**
	 *
	 *
	 * @param unknown $name
	 * @param unknown $relationship
	 * @return unknown
	 */
	public function addChild($name, $relationship) {
		$this->_children[$name] = $relationship;
		return true;
	}


	/**
	 *
	 *
	 * @param unknown $name
	 * @param unknown $relationship
	 * @return unknown
	 */
	public function addParent($name, $relationship) {
		$this->_parents[$name] = $relationship;
		return true;
	}


	/**
	 *
	 *
	 * @param unknown $type
	 * @return unknown
	 */
	public function getChild($type) {
		if (isset($this->_children[$type])) {
			return $this->_children[$type];
		}
		return false;
	}


	/**
	 *
	 *
	 * @param unknown $type
	 * @return unknown
	 */
	public function getParent($type) {
		if (isset($this->_parents[$type])) {
			return $this->_parents[$type];
		}
		return false;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getChildren() {
		$children = array();
		foreach ($this->_children as $key => $child) {
			if (!$child->active) { continue; }
			$children[$key] = $child;
		}
		return $children;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getParents() {
		$parents = array();
		foreach ($this->_parents as $key => $parent) {
			if (!$parent->active) { continue; }
			$parents[$key] = $parent;
		}
		return $parents;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getActive() {
		if (!is_null($this->_module) && $this->checked) {
			return true;
		}
		return false;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getChecked() {
		if (is_null($this->_checked)) {
			$this->_checked = true;
			foreach ($this->_module->dependencies() as $dep) {
				if (!Yii::$app->types->has($dep, false)) {
					$this->_checked = false;
				}
			}
		}
		return $this->_checked;
	}


}
