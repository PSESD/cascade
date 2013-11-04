<?php
/**
 * ./app/components/web/widgets/RWidgetItem.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


namespace app\components\web\widgets;

use Yii;

class Item extends \infinite\base\collector\Item {
	public $name;
	public $widget;
	public $tab;
	public $displayPriority = 0;
	public $locations = array();
	protected $_section;


	public function getObject() {
		if (is_null($this->widget)) {
			return null;
		}
		return Yii::createObject($this->widget);
		// we don't want these recycled, right?
		// if (!isset($this->_object) && !is_null($this->widget)) {
		// 	$this->_object = Yii::createObject($this->widget);
		// }
		// return $this->_object;
	}

	public function getSection($parent = null, $settings = array()) {
		if (is_null($this->_section)) {
			$this->_section = $this->owner->getSection($parent, $settings);
		}
		if (is_callable($this->_section) OR (is_array($this->_section) AND !empty($this->_section[0]) AND is_object($this->_section[0]))) {
			return $this->evaluateExpression($this->_section, array('parent' => $parent, 'settings' => $settings));
		}
		return $this->_section;
	}

	public function setSection($value) {
		$this->_section = $value;
	}

}


?>
