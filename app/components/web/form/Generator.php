<?php
/**
 * ./app/components/web/form/RFormGenerator.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\form;

use \infinite\helpers\Html;

class Generator extends \infinite\base\Object {
	protected $_items;
	public $isValid = true;
	public $class = '';
	public $ajax = false;

	/**
	 *
	 */
	public function __construct() {
		$this->_items = func_get_args();
		if (isset($this->_items[0]) and is_array($this->_items[0])) {
			$this->_items = $this->_items[0];
		}
		foreach ($this->_items as $item) {
			if (!$item->isValid) {
				$this->isValid = false;
			}
		}
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function get() {
		if (empty($this->_items)) {
			return '';
		}
		if ($this->ajax) {
			$this->class .= " ajaxSubmit";
		}
		$result = array();
		$result[] = Html::beginForm('', 'post', array('class' => $this->class));
		$result[] = Html::beginTag('div', '', array('class' => 'form'));
		foreach ($this->_items as $item) {
			$result[] = $item->get();
		}
		if (!Yii::app()->request->isAjaxRequest) {
			$result[] = Html::beginTag('div', '', array('class' => 'buttons'));
			$result[] = Html::submitButton('Save');
			$result[] = Html::endTag('div');
		}
		$result[] = Html::endTag('div');
		$result[] = Html::endForm();
		return implode("\n", $result);
	}


	/**
	 *
	 */
	public function render() {
		echo $this->get();
	}


}


?>
