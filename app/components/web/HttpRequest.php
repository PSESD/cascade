<?php
/**
 * ./protected/components/web/request/RHttpRequest.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


class RCascadeHttpRequest extends RHttpRequest {
	protected $_object;
	protected $_parent;

	public function init() {
		parent::init();
		Yii::app()->attachEventHandler('onBeginRequest', array($this, 'startRequest'));
	}

	public function startRequest() {
		if (isset($_GET['parent'])) {
			$this->parent = Registry::getObject($_GET['parent']);
		}	
	}

	public function setObject($object) {
		$this->_object = $object;
	}

	public function getObject() {
		return $this->_object;
	}

	public function setParent($object) {
		$this->_parent = $object;
	}

	public function getParent() {
		return $this->_parent;
	}
}


?>
