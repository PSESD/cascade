<?php

namespace app\modules\WidgetWatching\widgets;

class Content extends \app\components\web\widgets\base\Widget
{
	public $title = 'Watching';
	public $icon = 'ic-icon-eye';

	public function getGridCellSettings() {
		$gridSettings = parent::getGridCellSettings();
		$gridSettings['columns'] = 6;
		$gridSettings['maxColumns'] = 12;
		return $gridSettings;
	}

	public function renderContent() {
		return 'noo<br ><br><br><br>hey';
	}
}
