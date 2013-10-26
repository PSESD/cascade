<?php
/**
 * ./app/components/web/widgets/RWidgetEngine.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


class RWidgetEngine extends CWidget {
	protected $_registry = array();
	protected $_registryLocations = array();
	var $producedWidgets = array();
	var $lastBuildId;
	// $params is for options that will be built into the widget so it can refresh itself
	// $base are things the widget should be able to get from the params, but you don't want to have it refetch it if you have it

	/**
	 *
	 *
	 * @param unknown $widgetName
	 * @param unknown $params     (optional)
	 * @param unknown $base       (optional)
	 * @param unknown $state      (optional)
	 * @return unknown
	 */
	public function build($widgetName, $params = array(), $base = array(), $state = array()) {
		if (is_object($widgetName)) {
			$widgetItem = $widgetName;
		} else {
			if (!isset($this->_registry[$widgetName])) {
				return false;
			}
			$widgetItem = $this->_registry[$widgetName];
		}
		ob_start();
		ob_implicit_flush(false);
		$widget = $this->createWidget($widgetItem->class, array_merge($widgetItem->settings, $params));
		$widget->Owner = $widgetItem->Owner;
		$widget->params = $base;
		$widget->recreateParams = $params;
		$widget->state = $state;
		$widget->run();
		$this->lastBuildId = $widget->getWidgetId();
		$this->producedWidgets[$widget->widgetId] = array('widget' => $widget->niceName, 'id' => $widget->getWidgetId(), 'params' => $widget->recreateParams);
		return ob_get_clean();
	}

	public function fetch($widgetName, $params = array(), $base = array(), $state = array()) {
		if (is_object($widgetName)) {
			$widgetItem = $widgetName;
		} else {
			if (!isset($this->_registry[$widgetName])) {
				return false;
			}
			$widgetItem = $this->_registry[$widgetName];
		}
		$widget = $this->createWidget($widgetItem->class, array_merge($widgetItem->settings, $params));
		$widget->Owner = $widgetItem->Owner;
		$base['task'] = 'fetch';
		$widget->params = $base;
		$widget->recreateParams = $params;
		$widget->state = $state;
		$this->lastBuildId = $widget->getWidgetId();
		return $widget->run();
	}

	/**
	 *
	 *
	 * @param unknown $location
	 * @param unknown $owner    (optional)
	 * @return unknown
	 */
	public function getLocation($location, $owner = null) {
		if (!isset($this->_registryLocations[$location])) {
			return array();
		}
		if (is_null($owner)) {
			return $this->_registryLocations[$location];
		} else {
			$result = array();
			foreach ($this->_registryLocations[$location] as $key => $widget) {
				if ($widget->Owner === $owner) {
					$result[$key] = $widget;
				}
			}
			return $result;
		}
	}


	/**
	 *
	 *
	 * @param unknown $owner
	 * @param unknown $widgets
	 * @return unknown
	 */
	public function register($owner, $widgets) {
		if (empty($widgets)) {
			return true;
		}
		foreach ($widgets as $widget) {
			if (!isset($widget['name']) or !isset($widget['name'])) {
				throw new RException("Attempted widget registration for ". get_class($owner) ." without proper setup.");
			}
			if (isset( $this->_registry[$widget['name']])) {
				throw new RException("{$widget['name']} has already been registered by ". get_class($this->_registry[$widget['name']]->Owner));
			}
			$this->_registry[$widget['name']] = new RWidgetItem($owner, $widget);
			foreach ($this->_registry[$widget['name']]->locations as $location) {
				if (empty($this->_registryLocations[$location])) { $this->_registryLocations[$location] = array(); }
				$this->_registryLocations[$location][$widget['name']] = $this->_registry[$widget['name']];
			}
		}
		return true;
	}


}


?>
