<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\Html;

class BaseList extends PanelWidget implements ObjectWidgetInterface, ListWidgetInterface {
	use ObjectWidgetTrait;
	use ListWidgetTrait;

	public function renderItemContent($model, $key, $index){
		return $model->descriptor;
	}
}