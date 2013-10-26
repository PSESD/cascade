<?php
/**
 * ./protected/components/page/RSectionRegistry.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\sections;

use Yii;
use Section;
use Module;

class Engine extends \infinite\base\Engine {
	private $_sections = [];


	/**
	 *
	 *
	 * @return unknown
	 */
	public function beforeRequest() {
		Yii::beginProfile('ModuleLoad');
		foreach (Yii::$app->modules as $module => $settings) {
			if (preg_match('/^Section/', $module) === 0) { continue; }
			$mod = Yii::$app->getModule($module);
		}
		Yii::endProfile('ModuleLoad');
		return parent::beforeRequest();
	}


	/**
	 *
	 *
	 * @param unknown $mod
	 * @return unknown
	 */
	public function ref($mod) {
		if (!isset($this->_sections[$mod])) {
			$this->_sections[$mod] = new Section($mod);
			Yii::trace("'{$mod}' object section has been registered");
		}
		return $this->_sections[$mod];
	}


	/**
	 *
	 *
	 * @param object  $mod
	 * @return unknown
	 */
	public function add(Module $mod) {
		$shortName = $mod->shortName;
		if (!isset($this->_sections[$shortName]) or get_class($this->_sections[$shortName]->module) !== 'RSectionModule') {
			$this->_sections[$shortName] = new Section($shortName, $mod);
			Yii::trace("'{$shortName}' section has been registered and loaded");
		} else {
			$this->_sections[$shortName]->module = $mod;
			Yii::trace("'{$shortName}' section has been loaded");
		}
		return true;
	}


	/**
	 *
	 *
	 * @param unknown $remove
	 * @return unknown
	 */
	public function remove($remove) {
		if (is_object($remove) and $remove instanceof Module) {
			$shortName = $remove->shortName;
			unset($this->_sections[$shortName]);
			return true;
		} elseif (is_string($remove)) {
			unset($this->_sections[$remove]);
			return true;
		}

		return false;
	}


	/**
	 *
	 *
	 * @param unknown $test
	 * @param unknown $activeOnly (optional)
	 * @return unknown
	 */
	public function has($test, $activeOnly = true) {
		if (is_object($test) and $test instanceof Module) {
			$shortName = $test->shortName;
			return isset($this->_sections[$shortName]) && (!$activeOnly or $this->_sections[$shortName]->active);
		} elseif (is_string($test)) {
			return isset($this->_sections[$test]) && (!$activeOnly or $this->_sections[$test]->active);
		}
		return false;
	}


	/**
	 *
	 *
	 * @param unknown $get             (optional)
	 * @param unknown $defaultSettings (optional)
	 * @return unknown
	 */
	public function get($get = null, $defaultSettings = null) {
		if ($get === null) {
			return $this->_sections;
		}
		if (isset($this->_sections[$get])) {
			return $this->_sections[$get]->module;
		}
		if (!is_null($defaultSettings)) {
			$defaultSettings['shortName'] = $get;
			$fly = new Module(null, null, $defaultSettings);
			$this->add($fly);
			return $this->get($fly->shortName);
		}
		return false;
	}




}


?>
