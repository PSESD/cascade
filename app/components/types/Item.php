<?php
namespace app\components\types;

class Item extends \infinite\base\collector\Item {
	protected $_children = [];
	protected $_parents = [];
	protected $_sections;
	protected $_checked;

	public function setObject($object) {
		parent::setObject($object);
		if (is_null($object)) { return true; }
		foreach ($this->object->children() as $key => $child) {
			$options = array();
			if (is_string($key)) {
				$options = $child;
				$child = $key;
			}
			$this->collector->addRelationship($this->name, $child, $options);
		}
		foreach ($this->object->parents() as $key => $parent) {
			$options = array();
			if (is_string($key)) {
				$options = $parent;
				$parent = $key;
			}

			$this->collector->addRelationship($parent, $this->name, $options);
		}
		return true;
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
		if (!is_null($this->hasObject()) && $this->checked) {
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
			foreach ($this->object->dependencies() as $dep) {
				if (!$this->collector->has($dep, false)) {
					$this->_checked = false;
				}
			}
		}
		return $this->_checked;
	}
}


?>
