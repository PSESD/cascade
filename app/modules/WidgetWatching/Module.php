<?php

namespace app\modules\WidgetWatching;

use Yii;

class Module extends \app\components\web\widgets\Module
{
	protected $_title = 'Watching';
	public $icon = 'ic-icon-eye';
	
	public $widgetNamespace = 'app\modules\TypePhoneNumber\widgets';
}
