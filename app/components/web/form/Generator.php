<?php
/**
 * ./app/components/web/form/RFormGenerator.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\form;

use Yii;

use \infinite\helpers\Html;

class Generator extends \infinite\base\Object {
	protected $_items;
	public $form;

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
			$item->owner = $this;
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
		list($this->form, $formStartRow) = ActiveForm::begin([
			'options' => ['class' => ''] //form-horizontal
		], false);
		$result[] = $formStartRow;
		// $result[] = Html::beginForm('', 'post', array('class' => $this->class));
		$result[] = Html::beginTag('div', array('class' => ''));
		foreach ($this->_items as $item) {
			$result[] = $item->get();
		}
		if (!Yii::$app->request->isAjax) {
			$result[] = Html::beginTag('div', array('class' => 'form-group'));
			$result[] = Html::beginTag('div', array('class' => 'col-sm-12'));
			$result[] = Html::submitButton('Save', ['class' => 'btn btn-primary']);;
			$result[] = Html::endTag('div');
			$result[] = Html::endTag('div');
		}
		$result[] = Html::endTag('div');
		$result[] = ActiveForm::end(false);
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
