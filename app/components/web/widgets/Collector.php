<?php
namespace app\components\web\widgets;

class Collector extends \infinite\base\collector\Module {
	public function getCollectorItemClass() {
		return '\app\components\web\widgets\Item';
	}

	public function getModulePrefix() {
		return 'Section';
	}
}
?>