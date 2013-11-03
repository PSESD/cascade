<?php
namespace app\components\taxonomy;


class Collector extends \infinite\base\collector\Collector
{
	
	public function getCollectorItemClass() {
		return '\app\components\taxonomy\Item';
	}

}
?>