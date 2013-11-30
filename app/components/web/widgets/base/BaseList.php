<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\Html;

class BaseList extends PanelWidget implements ObjectWidgetInterface, ListWidgetInterface {
	use ObjectWidgetTrait;
	use ListWidgetTrait;

	public function renderItem($model, $key, $index){
		return Html::tag('li', $model->descriptor);
	}
}