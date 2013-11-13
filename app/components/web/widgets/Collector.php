<?php
namespace app\components\web\widgets;

use Yii;

class Collector extends \infinite\base\collector\Module {
	public $producedWidgets = array();
	public $lastBuildId;

	public function getCollectorItemClass() {
		return '\app\components\web\widgets\Item';
	}

	public function getModulePrefix() {
		return 'Widget';
	}

	public function build($widgetName, $recreateParams = array(), $baseParams = array(), $state = array()) {
		if (is_object($widgetName)) {
			$widget = $widgetName;
		} else {
			$widget = $this->getOne($widgetName);
		}
		if (is_null($widget->object)) {
			return false;
		}

		$widgetObject = $widget->object;
		$widgetObject->owner = $widget->owner;
		$widgetObject->params = $baseParams;
		$widgetObject->recreateParams = $recreateParams;
		$widgetObject->state = $state;

		$cell = $widgetObject->cell;

		$this->lastBuildId = $widgetObject->getWidgetId();
		$this->producedWidgets[$widgetObject->widgetId] = array('widget' => $widgetObject->systemId, 'id' => $widgetObject->widgetId, 'params' => $widgetObject->recreateParams);

		return $cell;
	}

	/**
	 *
	 *
	 * @param unknown $location
	 * @param unknown $owner    (optional)
	 * @return unknown
	 */
	public function getLocation($location, $owner = null) {
		$bucket = $this->getBucket('locations:'.$location);
		if (is_null($owner)) {
			return $bucket->toArray();
		} else {
			$result = [];
			foreach ($bucket as $key => $widget) {
				if ($widget->owner === $owner) {
					$result[$key] = $widget;
				}
			}
			return $result;
		}
	}
}
?>