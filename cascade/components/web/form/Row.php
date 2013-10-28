<?php
/**
 * ./app/components/web/form/RFormRow.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\form;

use \infinite\helpers\Html;

class Row extends \infinite\base\Object {
	protected $_items;
	public $distribution;
	public $compressed = false;

	/**
	 *
	 */
	public function __construct() {
		$this->_items = func_get_args();
		if (isset($this->_items[0]) and is_array($this->_items[0])) {
			$this->_items = $this->_items[0];
		}
	}


	/**
	 *
	 *
	 * @param unknown $model     (optional)
	 * @param unknown $formField (optional)
	 * @return unknown
	 */
	public function get($model = null, $formField = array()) {
		if (empty($this->_items)) {
			return '';
		}
		if ($this->compressed) {
			$baseClass = 'row row-compressed';
		} else {
			$baseClass = 'row';
		}
		$result = array();
		if (count($this->_items) === 1 and false) { // temporary disabled
			$result[] = Html::beginTag('div', '', array('class' => $baseClass.' single-row'));
			$result[] = $this->_items[0]->get($model, $formField);
		} else {
			$result[] = Html::beginTag('div', '', array('class' => $baseClass));
			$rowContent = '';
			$total = $left = count($this->_items);
			$widthLeft = 100;
			$n = 0;
			foreach ($this->_items as $item) {
				if (is_null($this->distribution)) {
					$width = round(100 / $total, 1);
				} else {
					if (isset($this->distribution[$n])) {
						$width = $this->distribution[$n];
					} elseif (isset($widthFroze)) {
						$width = $widthFroze;
					} else {
						$width = $widthFroze = round($widthLeft / count($left), 1);
					}
				}
				$widthLeft = $widthLeft - $width;
				if ($item === false) {
					$rowContent .= Html::tag('td', '&nbsp;', array('style' => 'width: '. $width .'%'));
				} else {
					$rowContent .= Html::tag('td', $item->get($model, $formField), array('style' => 'width: '. $width .'%'));
				}
				$n++;
			}
			$result[] = Html::tag('table', Html::tag('tr', $rowContent), array('class' => 'form-row'));
		}
		$result[] = Html::endTag('div');
		return implode("\n", $result);
	}


	/**
	 *
	 *
	 * @param unknown $model     (optional)
	 * @param unknown $formField (optional)
	 */
	public function render($model = null, $formField = array()) {
		echo $this->get($model, $formField);
	}


}


?>
