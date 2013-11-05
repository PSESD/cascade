<?php
/**
 * ./app/components/web/widgets/RBaseWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\StringHelper;


abstract class Widget extends \yii\base\Widget {
	use \infinite\base\ObjectTrait;
	use \infinite\base\ComponentTrait;

	public $owner;
	public $instanceSettings;

	public $title = false;
	public $icon = false;

	public $params = array();
	public $recreateParams = array();
	public $classes = array('ic-widget');

	protected $_widgetId;
	protected $_systemId;
	protected $_state;

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getState() {
		return $this->_state;
	}


	/**
	 *
	 *
	 * @param unknown $state
	 */
	public function setState($state) {
		$this->_state = $state;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getWidgetId() {
		if (!is_null($this->_widgetId)) {
			return $this->_widgetId;
		}
		return $this->_widgetId = 'ic-widget-'.md5(uniqid());
	}

	public function setWidgetId($value) {
		$this->_widgetId = $value;
	}
	
	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSystemId() {
		return self::baseClassName();
	}
}


?>
