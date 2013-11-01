<?php
/**
 * ./app/components/objects/RObjectRelationship.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
namespace app\components\types;

use Yii;

use \infinite\base\exceptions\Exception;

class Relationship extends \infinite\base\Object {
	protected $_parent;
	protected $_child;

	protected $_defaultOptions = array(
		'allowPrimary' => true, 
		'taxonomy' => null,
		'fields' => array(),
		'uniqueParent' => false, // only 1 parent of this type for this child (rare)
		'uniqueChild' => false, // only 1 child of this type for this parent
	);
	protected $_options = array();
	static $_relationships = array();


	/**
	 *
	 *
	 * @param object  $parent
	 * @param object  $child
	 * @param unknown $options (optional)
	 */
	public function __construct(Type $parent, Type $child, $options = array()) {
		$this->_parent = $parent;
		$this->_child = $child;
		$this->mergeOptions($options);
	}

	public function __get($name) {
		if (array_key_exists($name, $this->_options)) {
			return $this->_options[$name];
		} elseif (array_key_exists($name, $this->_defaultOptions)) {
			return $this->_defaultOptions[$name];
		}
		return parent::__get($name);
	}

	public function __isset($name) {
		if (array_key_exists($name, $this->_options)) {
			return isset($this->_options[$name]);
		} elseif (array_key_exists($name, $this->_defaultOptions)) {
			return isset($this->_defaultOptions[$name]);
		}
		return parent::__get($name);
	}
	/**
	 *
	 *
	 * @param object  $parent
	 * @param object  $child
	 * @param unknown $options (optional)
	 * @return unknown
	 */
	static public function get(Type $parent, Type $child, $options = array()) {
		$key = md5($parent->name ."-". $child->name);
		if (isset(self::$_relationships[$key])) {
			self::$_relationships[$key]->mergeOptions($options);
		} else {
			self::$_relationships[$key] = new Relationship($parent, $child, $options);
		}
		return self::$_relationships[$key];
	}



	/**
	 *
	 *
	 * @param unknown $newOptions
	 */
	public function mergeOptions($newOptions) {
		foreach ($newOptions as $k => $v) {
			if (array_key_exists($k, $this->_options)) {
				if ($this->_options[$k] !== $v) {
					throw new Exception("Conflicting relationship settings between parent: {$this->parent->name} and child: {$this->child->name}!");
				}
			} else {
				$this->_options[$k] = $v;
			}
		}
		$this->_options = array_merge($this->_options, $newOptions);
	}

	public function setDefaultOptions() {
		foreach ($this->_defaultOptions as $k => $v) {
			if (!array_key_exists($k, $this->_options)) {
				$this->_options[$k] = $v;
			}
		}
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getParent() {
		return $this->_parent->module;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getChild() {
		return $this->_child->module;
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getActive() {
		return (isset($this->_child) AND $this->_child->active) and (isset($this->_parent) AND $this->_parent->active);
	}

	public function getOptions() {
		return array_merge($this->_defaultOptions, $this->_options);
	}
}


?>
