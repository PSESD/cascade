<?php
/**
 * ./app/components/web/widgets/RBaseWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\base;

use Yii;

use \app\components\helpers\StringHelper;

use \infinite\helpers\Html;

use \yii\bootstrap\Nav;


abstract class Widget extends \yii\bootstrap\Widget implements \infinite\base\WidgetInterface {
	use \infinite\base\ObjectTrait;
	use \infinite\base\ComponentTrait;
	use \infinite\web\grid\CellContentTrait;
	use \infinite\web\RenderTrait;

	public $owner;
	public $instanceSettings;

	public $title = false;
	public $icon = false;

	public $params = array();
	public $recreateParams = array();
	public $htmlOptions = ['class' => 'ic-widget '];

	public $gridCellClass = '\infinite\web\grid\Cell';

	protected $_widgetId;
	protected $_systemId;
	protected $_settings;
	protected $_gridCell;

	abstract public function generateContent();

	// public function init() {
	// 	parent::init();
	// 	$backtrace = debug_backtrace();
	// 	$backtrace = $backtrace[4];
	// 	echo self::className() ." ({$backtrace['file']}:{$backtrace['line']})<br/>\n";
	// }
	public function getGridCellSettings() {
		return [
			'columns' => 3,
			'maxColumns' => 6
		];
	}

	public function getCell() {
		if (is_null($this->_gridCell)) {
			$gridCellClass = $this->gridCellClass;
			$objectSettings = $this->gridCellSettings;
			$objectSettings['class'] = $gridCellClass;
			$objectSettings['content'] = $this;
			$this->_gridCell = Yii::createObject($objectSettings);
		}
		return $this->_gridCell;
	}

	public function getHeaderMenu() {
		return [];
	}

	public function render() {
		echo $this->generate();
	}

	public function renderView($view, $params = []) {
		return parent::render($view, $params);
	}

	public function run() {
		echo $this->generate();
	}

	public function generateStart() {
		$parts = [];
		$parts[] = Html::beginTag('div', $this->htmlOptions);
		return implode("", $parts);
	}

	public function generateEnd() {
		$parts = [];
		$parts[] = Html::endTag('div'); // panel
		return implode("", $parts);
	}

	public function generateHeader() {
		return null;
	}

	public function generateFooter() {
		return null;
	}

	public function generate() {
		Yii::beginProfile(get_called_class() .':'. __FUNCTION__);
		$result = $this->generateStart() . $this->generateHeader() . $this->generateContent() . $this->generateFooter() . $this->generateEnd();
		Yii::endProfile(get_called_class() .':'. __FUNCTION__);
		return $result;
	}

	public function parseText($text) {
		return StringHelper::parseText($text, $this->variables);
	}

	public function getVariables() {
		return [];
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSettings() {
		return $this->_settings;
	}


	/**
	 *
	 *
	 * @param unknown $state
	 */
	public function setSettings($settings) {
		$this->_settings = $settings;
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
