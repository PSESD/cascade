<?php
/**
 * ./app/components/sections/RSection.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\sections;

class Section extends \infinite\base\Object {
	private $_name;
	private $_module;


	/**
	 *
	 *
	 * @param unknown $name
	 * @param unknown $module (optional)
	 */
	public function __construct($name, $module = null) {
		$this->_name = $name;
		$this->module = $module;
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


}
