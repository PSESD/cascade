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


abstract class PanelWidget extends Widget {
	public $panelCssClass = 'panel';
	public $panelStateCssClass = 'panel-default';
	public $gridCellClass = '\infinite\web\grid\Cell';

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
		Html::addCssClass($this->htmlOptions, $this->panelCssClass);
		Html::addCssClass($this->htmlOptions, $this->panelStateCssClass);
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
}


?>