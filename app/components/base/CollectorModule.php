<?php
namespace app\components\base;

use Yii;

abstract class CollectorModule extends \infinite\base\Module {
	protected $_collectorItem;

	abstract public function getCollectorName();


	public function getCollectorItem() {
		return $this->_collectorItem;
	}

	/**
	 * @inheritdoc
	 */
	public function __construct($id, $parent, $config=null) {
		if (!isset(Yii::$app->collectors[$this->collectorName])) { throw new Exception('Cannot find the collector '. $this->collectorName .'!'); }
		if (!($this->_collectorItem = Yii::$app->collectors[$this->collectorName]->register($this))) { throw new Exception('Could not register '. $this->shortName .' in '. $this->collectorName .'!'); }
		
		Yii::$app->collectors[$this->collectorName]->onAfterLoad(array($this, 'onAfterLoad'));

		if (isset(Yii::$app->controller)) {
			throw new Exception("This is a happy exception!");
			Yii::$app->controller->on(Controller::EVENT_BEFORE_ACTION, array($this, 'onBeforeControllerAction'));
		}
		
		parent::__construct($id, $parent, $config);
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

	public function onAfterLoad($event) {
		return true;
	}
}

?>