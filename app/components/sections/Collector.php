<?php
namespace app\components\sections;

class Collector extends \infinite\base\ModuleCollector {
	public function getCollectorItemClass() {
		return '\app\components\web\sections\Item';
	}

	public function getModulePrefix() {
		return 'Section';
	}
}
?>