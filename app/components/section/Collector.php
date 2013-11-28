<?php
namespace app\components\section;

use Yii;

class Collector extends \infinite\base\collector\Module {
	public function getCollectorItemClass()
	{
		return '\app\components\section\Item';
	}

	public function getModulePrefix()
	{
		return 'Section';
	}

	public function getInitialItems()
	{
		return [
			'_side' => Yii::createObject(['class' => 'app\components\section\base\Side'])
		];
	}
}
?>