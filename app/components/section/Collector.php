<?php
namespace app\components\section;

class Collector extends \infinite\base\collector\Module {
	public function getCollectorItemClass() {
		return '\app\components\section\Item';
	}

	public function getModulePrefix() {
		return 'Section';
	}
}
?>