<?php
/**
 * ./app/components/web/widgets/RBaseRelationshipWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


namespace app\components\web\widgets\core;

use Yii;

use \infinite\helpers\Html;
use \infinite\db\behaviors\Relatable;

abstract class Relationship extends DasboardBrowse {
	public $view = '\app\views\app\widgets\relationship\index';
	public $descriptorField = 'link';
	

	public function getGridTemplate() {
		$t = array (
			$this->fieldPrefix.$this->descriptorField,
		);

		if (!empty($this->instanceSettings['relationship']->taxonomy)) {
			$t[] = 'taxonomy_ids';
		}

		if (in_array('start', $this->instanceSettings['relationship']->options['fields'])) {
			$t[] = 'dateRange';
		}
		return $t;
	}


	public function getItemMenu() {
		$m = array();

		if (!empty($this->instanceSettings['relationship']->allowPrimary)) {
			$m[] =array(
					'icon' => 'ic-icon-star',
					'url' => Yii::$app->urlManager->createUrl(array('setPrimary', 'id' => '{id}', 'object' => $this->objectType)),
					'label' => 'Make Primary '.$this->instanceSettings['relationship']->{$this->objectType}->title->getSingular(true),
					'visible' => array('primary' => 0),
					'aclAction' => 'update',
				);
		}
		$m[] =array(
				'icon' => 'ic-icon-link',
				'url' => Yii::$app->urlManager->createUrl(array('link', 'module' => $this->Owner->shortName, 'id' => '{id}', $this->instanceSettings['whoAmI'] .'_object_id' => '{'.$this->instanceSettings['whoAmI'].'_object_id}')),
				'label' => 'Update relationship',
				'aclAction' => 'update',
			);
		$m[] = array(
			'icon' => 'ic-icon-trash_stroke',
			'url' => Yii::$app->urlManager->createUrl(array('delete', 'relation_id' => '{id}', 'object' => $this->objectType)),
			'label' => 'Delete '. $this->Owner->title->getSingular(true),
			'aclAction' => 'delete',
		);
		return $m;
	}

	public function renderPartial($view, $extra = array()) {
		if (!isset($this->gridTitleExtraVariables['relationship'])) { $this->gridTitleExtraVariables['relationship'] = null; }
		if (isset($this->instanceSettings['relationship'])
			AND isset($this->instanceSettings['relationship']->child)
			AND isset($this->instanceSettings['relationship']->parent)
			AND $this->instanceSettings['relationship']->child->shortName === $this->instanceSettings['relationship']->parent->shortName
			AND isset($this->recreateParams['instanceSettings']['whoAmI'])
		) {
			if ($this->recreateParams['instanceSettings']['whoAmI'] === 'child') {
				$this->gridTitleExtraVariables['relationship'] = 'Parent';
			} else {
				$this->gridTitleExtraVariables['relationship'] = 'Child';
			}
		}

		parent::renderPartial($view, $extra);
	}

	public function getItems() {
		Yii::app()->request->object = $this->params['object'];

		$model = Relatable::RELATION_MODEL;
		$items = new $model('search');
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			$items->parent_object_id = $this->params['object']->id;
		} else {
			$items->child_object_id = $this->params['object']->id;
		}
		return $items;
	}
	

	public function getObjectColumns() {
		$self = $this;
		$grid = array();
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			$keys = $this->instanceSettings['relationship']->child->dummyModel->getTableFields('childObject', $this->instanceSettings['relationship']->parent);
		} else {
			$keys = $this->instanceSettings['relationship']->parent->dummyModel->getTableFields('parentObject', $this->instanceSettings['relationship']->child);
		}
		$descriptorField = $this->fieldPrefix.$this->descriptorField;
		if (!isset($grid[$descriptorField])) {
			$grid = array_merge(array($descriptorField => array()), $grid);
		}
		if ($this->instanceSettings['relationship']->child->dummyModel->descriptorField !== $this->descriptorField) {
			unset($keys[$this->instanceSettings['relationship']->child->dummyModel->descriptorField]);
		}
		foreach ($keys as $key => $settings) {	
			if (is_numeric($key)) {
				$key = $settings;
				$settings = array();
			}
			//if ($key === $this->instanceSettings['relationship']->child->dummyModel->descriptorField) { $key = 'link'; }
			$grid[$this->fieldPrefix . $key] = array_merge(array(
				'class' => '\app\web\widgets\grid\columns\Grid',
				'name' => $this->fieldPrefix . $key,
				//'htmlOptions' => array('class' => 'data-cell-center'),
				'type' => 'html',
				'value' => function ($data, $row) use ($self) {
					return $self->getObject($data)->{$key};
				},
			), $settings);
		}
		if (!empty($this->instanceSettings['relationship']->taxonomy)) {
			$taxonomy = Yii::app()->taxonomyEngine->get($this->instanceSettings['relationship']->taxonomy);
			if (!empty($taxonomy)) {
				$grid['taxonomy_ids'] = array(
					'class' => '\app\web\widgets\grid\columns\Grid',
					'name' => 'taxonomy_ids',
					'htmlOptions' => array('class' => 'data-cell-center'),
					'type' => 'html',
					'value' => function ($data, $row) use ($taxonomy) {
						return $data->getHumanTaxonomyList($taxonomy);
					},
				);
			}
		}
		if (in_array('start', $this->instanceSettings['relationship']->fields)) {
			$grid['dateRange'] = array(
				'class' => '\app\web\widgets\grid\columns\Grid',
				'name' => 'dateRange',
				'htmlOptions' => array('class' => 'data-cell-center'),
				'type' => 'html',
				'value' => function ($data, $row) {
					return $data->dateRange;
				},
			);
		}
		$grid['primary'] = array(
			'class' => '\app\web\widgets\grid\columns\Grid',
			'visible' => false,
			'value' => function ($data, $row) {
				return $data->primary;
			},
		);
		//RDebug::d($grid);exit;
		return $grid;
	}
}


?>
