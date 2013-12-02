<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\base\exceptions\Exception;
use \infinite\helpers\ArrayHelper;

use \app\components\types\Relationship;

trait ObjectWidgetTrait
{
	protected $_dataProvider;

	public function getDataProvider() {
		if (is_null($this->_dataProvider)) {
			$dataProvider = $this->dataProviderSettings;
			if (!isset($dataProvider['class'])) {
				$dataProvider['class'] = 'yii\data\ActiveDataProvider';
			}
			$method = ArrayHelper::getValue($this->settings, 'queryRole', 'all');
			if (in_array($method, ['parents', 'children']) && empty(Yii::$app->request->object)) {
				throw new Exception("Object widget requested when no object has been set!");
			}
			$queryModelClass = $this->owner->primaryModel;
			switch ($method) {
				case 'parents':
					$dataProvider['query'] = Yii::$app->request->object->relativesQuery('parents', $queryModelClass);
				break;
				case 'children':
					$dataProvider['query'] = Yii::$app->request->object->relativesQuery('children', $queryModelClass);
				break;
				default:
					$dataProvider['query'] = $queryModelClass::find();
				break;
			}
			//var_dump($dataProvider['query']);exit;
			$dataProvider['pagination'] = $this->paginationSettings;
			$this->_dataProvider = Yii::createObject($dataProvider);
		}
		return $this->_dataProvider;
	}

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

	public function getHeaderMenu()
	{
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
		$typePrefix = null;
		if (Yii::$app->gk->canGeneral('create', $this->owner->primaryModel)) {
			$createUrl = ['object/create'];
			$method = ArrayHelper::getValue($this->settings, 'queryRole', 'all');
			if (in_array($method, ['parents', 'children'])) {
				if (empty(Yii::$app->request->object) || empty($this->settings['relationship'])) {
					throw new Exception("Object widget requested when no object has been set!");
				}
				$createUrl['object_id'] = Yii::$app->request->object->primaryKey;
				if ($method === 'parents') {
					$typePrefix = 'parent:';
				} else {
					$typePrefix = 'child:';
				}
			}
			$createUrl['type'] = $typePrefix . $this->owner->systemId;
			$menu[] = [
				'label' => '<i class="glyphicon glyphicon-plus"></i>',
				'linkOptions' => ['title' => 'Create'],
				'url' => $createUrl
			];
		}
		return $menu;
	}

	public function getPaginationSettings() {
		return ['class' => 'yii\data\Pagination', 'pageSize' => 20];
	}

	public function getPagerSettings() {
		return ['class' => 'yii\widgets\LinkPager'];
	}

	public function getDataProviderSettings() {
		return ['class' => 'yii\data\ActiveDataProvider'];
	}
}
?>