<?php
namespace app\components\dataInterface;

class Collector extends \infinite\base\ModuleCollector {
	public function getCollectorItemClass() {
		return '\app\components\dataInterface\Item';
	}
	
	public function getModulePrefix() {
		return 'Interface';
	}
}
?>