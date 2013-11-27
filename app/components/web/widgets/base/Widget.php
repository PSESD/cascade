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
	protected $_state;
	protected $_gridCell;

	abstract public function generateContent();

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

	public function generatePanelTitle() {
		$parts = [];
		if ($this->title) {
			$menu = null;
			$titleMenu = $this->generateTitleMenu();
			if ($titleMenu) {
				$menu = $titleMenu;
			}
			if (!empty($this->icon)) {
				$icon = Html::tag('i', '', ['class' => $this->icon]) . Html::tag('span', '', ['class' => 'break']);
			}
			$parts[] = Html::tag('div', Html::tag('h2', $icon . $this->parseText($this->title)) . $menu, ['class' => 'panel-heading']);
		}
		if (empty($parts)) {
			return false;
		}
		return implode("", $parts);
	}

	public function generateHeader() {
		$parts = [];
		Html::addCssClass($this->htmlOptions, 'panel');
		Html::addCssClass($this->htmlOptions, 'panel-default');
		$parts[] = Html::beginTag('div', $this->htmlOptions);
		$title = $this->generatePanelTitle();
		if ($title) {
			$parts[] = $title;
		}
		$parts[] = Html::beginTag('div', ['class' => 'panel-body']);
		return implode("", $parts);
	}

	public function generateTitleMenu() {
		$menu = $this->getHeaderMenu();
		if (empty($menu)) { return false; }
		$this->backgroundifyMenu($menu);
		return Nav::widget([
			'items' => $menu,
			'encodeLabels' => false,
			'options' => ['class' => 'pull-right nav-pills']
		]);
	}

	protected function backgroundifyMenu(&$items) {
		if (!is_array($items)) { return; }
		foreach ($items as $k => $v) {
			if (!isset($items[$k]['linkOptions'])) { $items[$k]['linkOptions'] = []; }
			if (!isset($items[$k]['linkOptions']['data-background'])) {
				$items[$k]['linkOptions']['data-handler'] = 'background';
			}
			if (isset($items[$k]['items'])) {
				$this->backgroundifyMenu($items[$k]['items']);
			}
		}
	}

	public function generateFooter() {
		$parts = [];
		$parts[] = Html::endTag('div'); // panel-body
		$parts[] = Html::endTag('div'); // panel
		return implode("", $parts);
	}

	public function run() {
		echo $this->generate();
	}

	public function generate() {
		Yii::beginProfile(get_called_class() .':'. __FUNCTION__);
		$result = $this->generateHeader() . $this->generateContent() . $this->generateFooter();
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
