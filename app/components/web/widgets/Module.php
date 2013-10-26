<?php
abstract class RWidgetModule extends RObjectModule {
	public $primaryModel = false;

	public function widgets() {
		$widgets = array();
		$className = 'R'. $this->shortName .'Widget';
		@class_exists($className);
		if (class_exists($className, false)) {
			$widget = array();
			$widget['name'] = $this->shortName .'Widget';
			$widget['class'] = $className;
			$widget['locations'] = array('parent_objects', 'child_objects');
			$widget['displayPriority'] = $this->priority;
			$widget['settings'] = array('gridTitleIcon' => $this->icon, 'gridTitle' => '%%type.'. $this->shortName .'.title%%');
			$widget['section'] = 
			$widgets[$widget['name']] = $widget;
		}

		return $widgets;
	}
}
?>