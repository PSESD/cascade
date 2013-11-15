<?php
namespace app\components\web\widgets\base;

use Yii;

use \yii\bootstrap\Button;

ini_set('memory_limit', -1);
class SimpleList extends BaseList {
	public function renderContent() {
		return get_class($this->owner);
	}

	public function getHeaderMenu() {
		$menu = [];
		if (Yii::$app->gk->canGeneral('create', $this->owner->primaryModel)) {
			$menu[] = [
				'label' => 'Create',
				'url' => '#'
			];
		}
		return $menu;
	}
}