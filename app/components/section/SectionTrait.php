<?php
namespace app\components\section;

trait SectionTrait {
	use \infinite\base\collector\CollectorTrait;

	protected $_title;
	public $icon = 'ic-icon-info';

	public function init() {
		parent::init();
		$this->registerMultiple($this, $this->defaultItems());
	}

	public static function generateSectionId($name) {
		return Inflector::slug($name);
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