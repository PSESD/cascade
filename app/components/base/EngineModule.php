<?php
namespace app\components\base;

use Yii;

abstract class EngineModule extends \infinite\base\Module {

	public function __construct($id, $parent, $config=null) {
		parent::__construct($id, $parent, $config);
		
		$this->loadSubmodules();
	}


	public function loadSubmodules() {
		$this->modules = $this->submodules;

		foreach ($this->submodules as $module => $settings) {
			$mod = $this->getModule($module);
			$mod->init();
		}
		return true;
	}

	public function getSubmodules() {
		return [];
	}

	public function onBeforeControllerAction($controller, $action) {
		return true;
	}

	public function onBeginRequest($event) {
		return true;
	}
}

?>