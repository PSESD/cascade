<?php

namespace app\modules\WidgetWatching;

use Yii;

class Module extends \app\components\web\widgets\Module
{
	protected $_title = 'Watching';
	public $icon = 'fa fa-eye';
	
	public $widgetNamespace = 'app\modules\WidgetWatching\widgets';


}
