<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\Html;

class Section extends Widget {
	public $gridClass = 'infinite\web\grid\Grid';
	public $section;

	public function init()
	{
		parent::init();
		if (isset($this->section)) {
			$this->icon = $this->section->icon;
			$this->title = $this->section->sectionTitle;
		}
	}

	public function generateHeader()
	{
		$parts = [];
		$parts[] = Html::tag('div', '', ['id' => 'section-'.$this->section->systemId, 'class' => 'scroll-mark']);
		if ($this->isSingle) {
			Html::addCssClass($this->htmlOptions, 'single-section');
			$parts[] = Html::beginTag('div', $this->htmlOptions);
		} else {
			$parts[] = parent::generateHeader();
		}
		return implode("", $parts);
	}


	public function generateFooter()
	{
		if ($this->isSingle) {
			$parts = [];
			$parts[] = Html::endTag('div');
			return implode("", $parts);
		}
		return parent::generateFooter();
	}

	public function generateContent()
	{
		$items = [];
		foreach ($this->widgets as $widget) {
			$items[] = $cell = Yii::$app->collectors['widgets']->build($widget->object);
			if ($this->isSingle) {
				$cell->columns = 12;
			}
		}
		$grid = Yii::createObject(['class' => $this->gridClass, 'cells' => $items]);
		return $grid->generate();
	}

	public function getIsSingle() {
		return count($this->widgets) === 1;
	}

	public function getWidgets() {
		return $this->section->getAll();;
	}
}
?>