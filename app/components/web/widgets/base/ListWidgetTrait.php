<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\Html;

trait ListWidgetTrait
{
	public $emptyMessage = 'No items exist.';

	protected $_dataProvider;

	public function getDataProvider() {
		if (is_null($this->_dataProvider)) {
			$dataProvider = $this->dataProviderSettings;
			if (!isset($dataProvider['class'])) {
				$dataProvider['class'] = 'yii\data\ActiveDataProvider';
			}
			$queryModelClass = $this->owner->primaryModel;
			$dataProvider['query'] = $queryModelClass::find();
			$dataProvider['pagination'] = $this->paginationSettings;
			$this->_dataProvider = Yii::createObject($dataProvider);
		}
		return $this->_dataProvider;
	}

	public function generateContent() {
		$results = $this->dataProvider;
		if (!empty($results->count)) {
			$models = $this->dataProvider->getModels();
			$keys = $this->dataProvider->getKeys();
			$rows = [];
			foreach (array_values($models) as $index => $model) {
				$rows[] = $this->renderItem($model, $keys[$index], $index);
			}
			return implode('', $rows);
		} else {
			return Html::tag('div', $this->emptyMessage, ['class' => 'empty-messages']);
		}
	}

	public function generateFooter()
	{
		$footer = '';
		$pager = $this->renderPager();
		if ($pager) {
			$footer = Html::tag('div', $pager, ['class' => 'panel-footer']);
		}
		return $footer . parent::generateFooter();
	}

	/**
	 * Renders the pager.
	 * @return string the rendering result
	 */
	public function renderPager()
	{
		$pagination = $this->dataProvider->getPagination();
		if ($pagination === false || $this->dataProvider->getCount() <= 0) {
			return false;
		}
		/** @var LinkPager $class */
		$pager - $this->pagerSettings;
		$class = ArrayHelper::remove($pager, 'class', 'yii\widgets\LinkPager');
		$pager['pagination'] = $pagination;
		return $class::widget($this->pager);
	}

	public function getPaginationSettings() {
		return ['class' => 'yii\data\Pagination', 'pageSize' => 20];
	}

	public function getPagerSettings() {
		return ['class' => 'yii\widgets\LinkPager', 'pageSize' => 20];
	}

	public function getDataProviderSettings() {
		return ['class' => 'yii\data\ActiveDataProvider'];
	}

}