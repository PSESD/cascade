<?php
namespace app\components\web\widgets\base;

use Yii;

class BaseList extends PanelWidget {
	public function getSortBy() {
		$sortBy = [];
		$sortBy[] = [
			'label' => 'Name'
		];
		$sortBy[] = [
			'label' => 'Familiarity'
		];
		return $sortBy;
	}

	public function getHeaderMenu() {
		$menu = [];
		$sortBy = $this->sortBy;
		if (!empty($sortBy)) {
			$item = [
				'label' => '<i class="glyphicon glyphicon-sort"></i>',
				'linkOptions' => ['title' => 'Sort by'],
				'url' => '#',
				'items' => []
			];
			foreach ($sortBy as $sortItem) {
				$item['items'][] = [
					'label' => $sortItem['label'],
					'linkOptions' => ['title' => 'Sort by '. $sortItem['label']],
					'url' => '#',
				];
			}
			$menu[] = $item;
		}
		
		if (Yii::$app->gk->canGeneral('create', $this->owner->primaryModel)) {
			$menu[] = [
				'label' => '<i class="glyphicon glyphicon-plus"></i>',
				'linkOptions' => ['title' => 'Create'],
				'url' => ['object/create', 'type' => $this->owner->systemId]
			];
		}
		return $menu;
	}

	public function generateContent() {
		
	}
}