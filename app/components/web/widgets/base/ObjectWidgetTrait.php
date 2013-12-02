<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\base\exceptions\Exception;
use \infinite\helpers\ArrayHelper;

use \app\components\types\Relationship;

trait ObjectWidgetTrait
{
	protected $_dataProvider;

	public function generateStart() {
		$this->htmlOptions['data-instructions'] = json_encode($this->refreshInstructions);
		return parent::generateStart();
	}

	public function getRefreshInstructions() {
		$i = [];
		$i['type'] = 'widget';
		$i['systemId'] = $this->collectorItem->systemId;
		// if (isset($this->settings['relationship'])) {
		// 	$i['relationship'] = [];
		// 	$i['relationship']['parent'] = $this->settings['relationship']->parent->systemId;
		// 	$i['relationship']['child'] = $this->settings['relationship']->child->systemId;
		// 	$i['relationship']['objectRole'] = ($this->settings['queryRole'] === 'children') ? 'parent' : 'child';

		// }
		$i['recreateParams'] = $this->recreateParams;
		return $i;
	}

	public function getWidgetClasses() {
		$classes = parent::getWidgetClasses();
		$classes[] = 'refreshable';
		$queryModelClass = $this->owner->primaryModel;
		$classes[] = 'model-'. $queryModelClass::baseClassName();
		return $classes;
	}

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
		$baseCreate = ['object/create'];
		$typePrefix = null;
		$method = ArrayHelper::getValue($this->settings, 'queryRole', 'all');
		$create = true;
		$link = false;

		if (($create || $link) && in_array($method, ['parents', 'children'])) {
			if (empty(Yii::$app->request->object) || empty($this->settings['relationship'])) {
				throw new Exception("Object widget requested when no object has been set!");
			}
			$create = $link = Yii::$app->gk->canGeneral('update', Yii::$app->request->object);
			$baseCreate['object_id'] = Yii::$app->request->object->primaryKey;
			if ($method === 'parents') {
				$typePrefix = 'parent:';
			} else {
				$typePrefix = 'child:';
			}
		}
		$baseCreate['type'] = $typePrefix . $this->owner->systemId;

		if ($create && Yii::$app->gk->canGeneral('create', $this->owner->primaryModel)) {
			$createUrl = $baseCreate;
			$menu[] = [
				'label' => '<i class="fa fa-plus"></i>',
				'linkOptions' => ['title' => 'Create'],
				'url' => $createUrl
			];
		}
		if ($link && Yii::$app->gk->canGeneral('create', $this->owner->primaryModel)) {
			$createUrl = $baseCreate;
			$createUrl['link'] = 1;
			$menu[] = [
				'label' => '<i class="fa fa-link"></i>',
				'linkOptions' => ['title' => 'Link'],
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