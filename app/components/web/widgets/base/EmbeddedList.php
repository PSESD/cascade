<?php
namespace app\components\web\widgets\base;
use \infinite\helpers\Html;

class EmbeddedList extends EmbeddedWidget implements ListWidgetInterface {
	use ListWidgetTrait;

	public function renderItem($model, $key, $index) {
		return Html::tag('li', $model->descriptor);
	}

	public function getPaginationSettings() {
		return false;
	}
}