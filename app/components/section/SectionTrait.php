<?php
namespace app\components\section;

use Yii;

use \app\components\helpers\StringHelper;
use \infinite\base\language\Noun;

trait SectionTrait {
	use \infinite\base\collector\CollectorTrait;
	use \infinite\web\RenderTrait;

	public $sectionWidgetClass = 'app\components\web\widgets\base\Section';
	protected $_title;
	protected $_widget;

	public $icon = 'fa fa-info';

	public function init() {
		parent::init();
		$this->registerMultiple($this, $this->defaultItems());
	}

	public static function generateSectionId($name) {
		return Inflector::slug($name);
	}

	public function getWidget() {
		if (is_null($this->_widget)) {
			$this->_widget = Yii::createObject(['class' => $this->sectionWidgetClass, 'section' => $this]);
		}
		return $this->_widget;
	}
	public function generate() {
		return $this->widget->generate();
	}

	public function setTitle($title) {
		$this->_title = $title;
	}

	public function getSectionTitle() {
		return StringHelper::parseText($this->_title);
	}

	/**
	 *
	 *
	 * @param unknown $parent (optional)
	 * @return unknown
	 */
	protected function defaultItems($parent = null) {
		return array();
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getTitle() {
		if (is_object($this->sectionTitle)) { return $this->sectionTitle; }
		return new Noun($this->sectionTitle);
	}
}
?>