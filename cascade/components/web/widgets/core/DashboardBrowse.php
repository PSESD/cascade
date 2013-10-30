<?php
/**
 * ./app/components/web/widgets/RDashboardBrowseWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\core;

use Yii;

use \app\models\Registry;

use \infinite\web\Response;
use \infinite\db\behaviors\Relatable;
use \infinite\db\behaviors\Access;

abstract class DashboardBrowse extends \app\components\web\widgets\base\Widget {
	public $objectId;
	public $view = '\app\views\app\widgets\relationship\index';
	public $viewStyleDefault = 'grid';

	abstract public function getGridTemplate();
	abstract public function getObjectColumns();

	public function getSortableAttributes() {
		return array();
	}

	public function getColumns() {
		$c = $this->getObjectColumns();
		$c['primary'] = array('visible' => false);
		$c[$this->fieldPrefix . 'id'] = array('visible' => false);
		$otherColumnId = $this->instanceSettings['whoAmI'] . '_object_id';
		$c[$otherColumnId] = array('visible' => false);

		$taxonomy = Yii::$app->taxonomyEngine->get($this->instanceSettings['relationship']->taxonomy);
		if (!empty($taxonomy)) {
			$c['taxonomy_ids'] = array(
				'class' => '\app\web\widgets\grid\columns\Grid',
				'name' => 'taxonomy_ids',
				'htmlOptions' => array('class' => 'data-cell-center'),
				'type' => 'html',
				'value' => function ($data, $row) use ($taxonomy) {
					return $data->getHumanTaxonomyList($taxonomy);
				},
			);
		}
		return $c;
	}
	

	public function getSpecialItemClasses() {
		return array(
			'widget-item-primary' => array('primary' => 1)
		);
	}

	public function getDataProvider() {
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			return $this->items->searchChildren($this->instanceSettings['relationship']->parent->primaryModel, $this->instanceSettings['relationship']->child->primaryModel, $this->state);
		} else {
			return $this->items->searchParents($this->instanceSettings['relationship']->parent->primaryModel, $this->instanceSettings['relationship']->child->primaryModel, $this->state);
		}
	}

	public function getObject($data) {
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			return $data->childObject;
		} else {
			return $data->parentObject;
		}
	}

	public function getFieldPrefix() {
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			return 'childObject.';
		} else {
			return 'parentObject.';
		}
	}
	public function getObjectType() {
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			return 'child';
		} else {
			return 'parent';
		}
	}

	public function getRendererSettings() {
		return array(
			'grid' => array(
				'templateContent' => $this->gridTemplate,
			)
		);
	}

	public function getViews() {
		return array('grid', 'table');
	}

	public function getCurrentView() {
		return 'grid';
	}

	public function getSettings() {
		return array(
			'id' => $this->widgetId .'-grid',
			'emptyText' => 'No '. $this->Owner->title->plural .' found.',
			'dataProvider' => $this->dataProvider,
			'state' => $this->state,
			'columns' => $this->columns,
			'sortableAttributes' => $this->sortableAttributes,
			'specialItemClasses' => $this->specialItemClasses,
			'views' => $this->views,
			'currentView' => $this->currentView,
			'itemMenu' => $this->itemMenu,
			'rendererSettings' => $this->rendererSettings,
		);
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
				'icon' => 'ic-icon-pen',
				'url' => Yii::$app->urlManager->createUrl(array('update', 'id' => '{'.$this->fieldPrefix.'id}')),
				'label' => 'Update '. $this->Owner->title->getSingular(true),
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
	/**
	 *
	 */
	public function run() {
		if (empty($this->params['object'])) {
			$this->params['object'] = Registry::getObject($this->objectId);
		}
		$response = new Response($this->view, array(), $this);
		$this->widgetTag = 'li';
		$this->grid = true;
		if (is_null($this->gridTitle) or $this->gridTitle === false) { $this->gridTitle = Yii::t('ic', $this->Owner->title->getPlural(true)); }

		if (is_null($this->gridTitleMenu)) {
			$this->gridTitleMenu = array();
		}
		if (Yii::$app->gk->canGeneral('create', $this->Owner->primaryModel)) {
			$this->gridTitleMenu[] = array('url' => array('/app/create', 'module' => $this->Owner->shortName, $this->instanceSettings['whoAmI'].'_object_id' => $this->objectId), 'icon' => 'ic-icon-plus', 'ajax' => true, 'title' => Yii::t('ic', 'Create and link '. $this->Owner->title->getSingular(false)));
		}
		if (!isset($this->instanceSettings['relationship'])) {
			$type = $this->params['object']->typeModuleRef;
			if (isset($this->instanceSettings['whoAmI']) and $this->instanceSettings['whoAmI'] === 'parent') {
				$this->instanceSettings['relationship'] = $type->getChild($this->Owner->shortName);
			} else {
				$this->instanceSettings['relationship'] = $type->getParent($this->Owner->shortName);
			}
		}
		$relationship = $this->instanceSettings['relationship'];
		if (!$relationship->uniqueChild) {
			$this->gridTitleMenu[] = array('url' => array('/app/link', 'module' => $this->Owner->shortName, $this->instanceSettings['whoAmI'].'_object_id' => $this->objectId), 'icon' => 'ic-icon-link', 'ajax' => true, 'title' => Yii::t('ic', 'Link an existing '. $this->Owner->title->getSingular(false)));
		}
		$this->params['items'] = $this->getItems();
		$response->handlePartial();
	}

	public function getItems() {
		Yii::$app->request->object = $this->params['object'];

		$model = Relatable::RELATION_MODEL;
		$items = new $model('search');
		if ($this->instanceSettings['whoAmI'] === 'parent') {
			$items->parent_object_id = $this->params['object']->id;
		} else {
			$items->child_object_id = $this->params['object']->id;
		}
		return $items;
	}

	public function renderPartial($view, $extra = array()) {
		Access::allowInherit();
		parent:: renderPartial($view, $extra);
		Access::denyInherit();
	}

}


?>
