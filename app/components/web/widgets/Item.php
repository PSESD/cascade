<?php
/**
 * ./app/components/web/widgets/RWidgetItem.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


namespace app\components\web\widgets;


class Item extends \infinite\base\Object {
	public $Owner;
	public $name;
	public $class;
	public $settings = array();
	public $tab;
	public $displayPriority = 0;
	public $locations = array();
	protected $_section;

	/**
	 *
	 *
	 * @param unknown $owner
	 * @param unknown $settings
	 */
	public function __construct($owner, $settings) {
		$this->Owner = $owner;
		foreach ($settings as $k => $v) {
			$this->{$k} = $v;
		}
	}

	public function getSection($parent = null, $settings = array()) {
		if (is_null($this->_section)) {
			$this->_section = $this->Owner->getSection($parent, $settings);
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
