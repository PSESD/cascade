<?php
namespace app\components\sections;

class Collector extends \infinite\base\collector\Module {
	public function getCollectorItemClass() {
		return '\app\components\web\sections\Item';
	}

	public function getModulePrefix() {
		return 'Section';
	}
}
?>