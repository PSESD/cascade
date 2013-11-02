<?php
namespace app\components\web\widgets;

abstract class Module extends \app\components\base\EngineModule {
	public $title;
	public $icon = 'ic-icon-info';
	public $widgetNamespace;

	/**
	 *
	 *
	 * @param unknown $id
	 * @param unknown $parent
	 * @param unknown $config (optional)
	 */
	public function __construct($id, $parent, $config=null) {
		parent::__construct($id, $parent, $config);
		
		if (!isset(Yii::$app->types)) { throw new Exception('Cannot find the object type registry!'); }
		if (!($this->_objectType = Yii::$app->types->add($this))) { throw new Exception('Could not register type '. $this->shortName .'!'); }

		Yii::$app->types->on(Engine::EVENT_AFTER_TYPE_REGISTRY, array($this, 'onBeginRequest'));
		if (isset(Yii::$app->controller)) {
			throw new Exception("This is a happy exception!");
			Yii::$app->controller->on(Controller::EVENT_BEFORE_ACTION, array($this, 'onBeforeControllerAction'));
		}
		$this->loadSubModules();
	}

	public function getModuleType() {
		return 'widget';
	}

	public function onBeginRequest($event) {
		if (isset(Yii::$app->widgetEngine) and !Yii::$app->widgetEngine->register($this, $this->widgets())) { throw new Exception('Could not register widgets for '. $this->shortName .'!'); }
	}

	public function widgets() {
		$widgets = [];
		$className = $this->widgetNamespace .'\\'. 'Content';
		@class_exists($className);
		if (class_exists($className, false)) {
			$widget = array();
			$widget['name'] = $this->shortName .'Content';
			$widget['class'] = $className;
			$widget['locations'] = array('parent_objects', 'child_objects');
			$widget['displayPriority'] = $this->priority;
			$widget['settings'] = array('gridTitleIcon' => $this->icon, 'gridTitle' => '%%type.'. $this->shortName .'.title%%');
			$widget['section'] = 
			$widgets[$widget['name']] = $widget;
		}

		return $widgets;
	}

	public function getShortName() {
		preg_match('/Widget([A-Za-z]+)\\\Module/', get_class($this), $matches);
		if (!isset($matches[1])) {
			throw new Exception(get_class($this). " is not set up correctly!");
		}
		return $matches[1];
	}

}
?>