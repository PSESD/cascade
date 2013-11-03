<?php
namespace app\components\dataInterface;

class Collector extends \infinite\base\collector\Module {
	public function getCollectorItemClass() {
		return '\app\components\dataInterface\Item';
	}
	
	public function getModulePrefix() {
		return 'Interface';
	}
}
?>