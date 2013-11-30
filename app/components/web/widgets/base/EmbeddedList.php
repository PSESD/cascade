<?php
namespace app\components\web\widgets\base;
use \infinite\helpers\Html;

class EmbeddedList extends EmbeddedWidget implements ListWidgetInterface {
	use ListWidgetTrait;

	public function renderItemContent($model, $key, $index) {
		return $model->descriptor;
	}

	public function getPaginationSettings() {
		return false;
	}
}