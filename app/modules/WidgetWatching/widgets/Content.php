<?php

namespace app\modules\WidgetWatching\widgets;

class Content extends \app\components\web\widgets\base\PanelWidget
{
	public $title = 'Watching';
	public $icon = 'fa-eye';

	public function getGridCellSettings() {
		$gridSettings = parent::getGridCellSettings();
		$gridSettings['columns'] = 6;
		$gridSettings['maxColumns'] = 12;
		return $gridSettings;
	}

	public function generateContent() {
		return 'noo<br ><br><br><br>hey';
	}
}
