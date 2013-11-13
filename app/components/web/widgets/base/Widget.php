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


abstract class Widget extends \yii\bootstrap\Widget implements \infinite\base\WidgetInterface {
	use \infinite\base\ObjectTrait;
	use \infinite\base\ComponentTrait;

	public $owner;
	public $instanceSettings;

	public $title = false;
	public $icon = false;

	public $params = array();
	public $recreateParams = array();
	public $classes = array('ic-widget');

	public $gridCellClass = '\infinite\web\grid\Cell';

	protected $_widgetId;
	protected $_systemId;
	protected $_state;
	protected $_gridCell;

	abstract public function renderContent();

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

	public function renderPanelTitle() {
		$parts = [];
		if ($this->title) {
			$menu = null;
			$parts[] = Html::tag('div', $this->parseText($this->title) . $menu, ['class' => 'panel-heading']);
		}
		if (empty($parts)) {
			return false;
		}
		return implode("", $parts);
	}

	public function renderHeader() {
		$parts = [];
		$parts[] = Html::beginTag('div', ['class' => 'panel panel-default']);
		$title = $this->renderPanelTitle();
		if ($title) {
			$parts[] = $title;
		}

		$parts[] = Html::beginTag('div', ['class' => 'panel-body']);
		return implode("", $parts);
	}


	public function renderFooter() {
		$parts = [];
		$parts[] = Html::endTag('div'); // panel-body
		$parts[] = Html::endTag('div'); // panel
		return implode("", $parts);
	}

	public function run() {
		echo $this->generate();
	}

	public function generate() {
		return $this->renderHeader() . $this->renderContent() . $this->renderFooter();
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
