<?php

namespace app\modules\TypeEmailAddress\widgets;

use \infinite\helpers\Html;

class EmbeddedList extends \app\components\web\widgets\base\SideList
{
	public function renderItemContent($model, $key, $index)
	{
		return Html::mailto($model->email_address, $model->email_address);
	}
}
