<?php
/**
 * ./app/components/objects/RObjectModule.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
namespace app\components\types;

use Yii;

use \app\models\Group;
use \app\models\Relation;
use \app\components\web\form\Generator as FormGenerator;

use \infinite\base\exceptions\Exception;
use \infinite\base\exceptions\HttpException;
use \infinite\base\language\Noun;

use \yii\base\Controller;

abstract class Module extends \app\components\base\CollectorModule {
	protected $_title;
	public $version = 1;

	public $objectSubInfo = array();
	public $icon = 'ic-icon-info';
	public $priority = 1000; //lower is better

	public $uniparental = false;
	public $selfManaged = true;

	public $sectionName;

	public $widgetNamespace;
	public $modelNamespace;

	public function init() {
		if (isset($this->modelNamespace)) {
			Yii::$app->registerModelAlias(':'. $this->systemId, $this->modelNamespace);
		}
		parent::init();
	}

	public function getCollectorName() {
		return 'types';
	}

	/**
	 *
	 *
	 * @param unknown $controller
	 * @param unknown $action
	 * @return unknown
	 */
	public function onBeforeControllerAction($controller, $action) {
		if (!isset($_SERVER['PASS_THRU']) or $_SERVER['PASS_THRU'] != md5(Yii::$app->params['salt'] . 'PASS')) {
			throw new HttpException(400, 'Invalid request!');
		}
		return parent::onBeforeControllerAction($event);
	}

	public function onAfterInit($event) {
		if (!isset(Yii::$app->collectors['taxonomies']) || !Yii::$app->collectors['taxonomies']->registerMultiple($this, $this->taxonomies())) { throw new Exception('Could not register widgets for '. $this->systemId .'!'); }
		if (!isset(Yii::$app->collectors['widgets']) || !Yii::$app->collectors['widgets']->registerMultiple($this, $this->widgets())) { throw new Exception('Could not register widgets for '. $this->systemId .'!'); }
		if (!isset(Yii::$app->collectors['roles']) || !Yii::$app->collectors['roles']->registerMultiple($this, $this->roles())) { throw new Exception('Could not register roles for '. $this->systemId .'!'); }	
		return parent::onAfterInit($event);
	}

	
	public function setup() {
		$results = array(true);
		if (!empty($this->primaryModel) AND !empty($this->collectorItem->parents)) {
			$groups = array('staff');
			foreach ($groups as $groupName) {
				$group = Group::getBySystemName($groupName, true);
				if (empty($group)) { continue; }
				if ($this->isChildless) {
					$results[] = Yii::$app->gk->inherit(array('read', 'delete', 'create', 'archive', 'update'), null, $group, $this->primaryModel);
				} else {
					$results[] = Yii::$app->gk->parentAccess(array('read', 'delete', 'create', 'archive', 'update'), null, $group, $this->primaryModel);
				}
			}
		}
		return min($results);
	}

	public function getPrimaryModel() {
		return $this->modelNamespace .'\\'. 'Object'.$this->systemId;
	}

	public function getModuleType() {
		return 'Type';
	}

	public function upgrade($from) {
		return true;
	}

	public function getPossibleRoles() {
		return Yii::$app->collectors['roles']->getRoles($this);
	}

	public function getPossibleRoleList() {
		return Yii::$app->collectors['roles']->getRoleList($this);
	}

	public function getCreatorRole() {
		return array();
	}

	public function getIsOwnable() {
		return false;
	}

	public function getOwnerObject() {
		return null;
	}

	public function getOwner() {
		if (!$this->isOwnable) {
			return null;
		}
		$ownerObject = $this->getOwnerObject();
		if (is_object($ownerObject)) {
			return $ownerObject->primaryKey;
		}
		return $ownerObject;
	}

	/**
	 *
	 *
	 * @param unknown $term
	 * @param unknown $limit (optional)
	 * @return unknown
	 */
	public function search($term, $params = array()) {
		throw new Exception("Who is calling this?");
		if (!$this->primaryModel) { return false; }
		$results = array();
		$model = $this->primaryModel;
		$model = new $model('search');
		$raw = $model->searchTerm($term, $params);
		return $raw;
	}

	public function getObjectLevel() {
		if ($this->isPrimaryType) {
			return 1;
		}
		$parents = $this->collectorItem->parents;
		if (!empty($parents)) {
			$maxLevel = 1;
			foreach ($parents as $rel) {
				if (get_class($rel->parent) === get_class($this)) { continue; }
				$newLevel = $rel->parent->objectLevel + 1;
				if ($newLevel > $maxLevel) {
					$maxLevel = $newLevel;
				}
			}
			return $maxLevel;
		}
		return 1;
	}
	/**
	 *
	 *
	 * @param unknown $parent   (optional)
	 * @param unknown $settings (optional)
	 * @return unknown
	 */
	public function getSection($parent = null, $settings = array()) {
		$name = $this->systemId;
		if (!empty($parent) and $parent->systemId === $this->systemId) {
			return Yii::$app->sections->get('related-'.$this->systemId, array('sectionTitle' => 'Related %%type.'. $this->systemId .'.title%%', 'icon' => $this->icon, 'systemId' => $settings['whoAmI'].'-'.$this->systemId, 'displayPriority' => -999));
		}
		$newSectionTitle = '%%type.'. $this->systemId .'.title%%';
		if (!is_null($this->sectionName)) {
			$section = Yii::$app->sections->get($this->sectionName);
			if (!empty($section)) {
				return $section;
			}
			$newSectionTitle = $this->sectionName;
		}
		return Yii::$app->sections->get($this->systemId, array('sectionTitle' => $newSectionTitle, 'icon' => $this->icon, 'systemId' => $this->systemId));
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	
	public function getTitle() {
		if (!is_object($this->_title)) {
			$this->_title = new Noun($this->_title);
		}
		return $this->_title;
	}


	public function widgets() {
		$widgets = array();
		$detailListClassName = self::classNamespace() .'\widgets\\'. 'DetailList';
		$simpleListClassName = self::classNamespace() .'\widgets\\'. 'SimpleList';
		@class_exists($detailListClassName);
		@class_exists($simpleListClassName);
		if (!$this->isChildless) {
			if (!class_exists($detailListClassName, false)) { $detailListClassName = false; }
			if (!class_exists($simpleListClassName, false)) { $simpleListClassName = false; }
			// needs widget for children and summary page
			if ($detailListClassName) {
				$childrenWidget = array();
				$id = 'Parent'. $this->systemId .'Browse';
				$childrenWidget['widget'] = [
					'class' => $detailListClassName,
					'icon' => $this->icon, 
					'title' => '%%relationship%% %%type.'. $this->systemId .'.title.upperPlural%%'
				];
				$childrenWidget['locations'] = array('child_objects');
				$childrenWidget['displayPriority'] = $this->priority;
				$widgets[$id] = $childrenWidget;
			} else {
				Yii::trace("Warning: There is no browse class for the child objects of {$this->systemId}");
			}
			if ($this->selfManaged AND $simpleListClassName) {
				$summaryWidget = array();
				$id = $this->systemId .'Summary';
				$summaryWidget['widget'] = [
					'class' => $simpleListClassName,
					'icon' => $this->icon, 
					'title' => '%%type.'. $this->systemId .'.title.upperPlural%%'
				];
				$summaryWidget['locations'] = array('front');
				$summaryWidget['displayPriority'] = $this->priority;
				$widgets[$id] = $summaryWidget;
			} else {
				Yii::trace("Warning: There is no summary class for {$this->systemId}");
			}
		} else {
			if (!class_exists($detailListClassName, false)) { $detailListClassName = false; }
			// needs widget for parents
		}
		if ($detailListClassName) {
			$parentsWidget = array();
			$id = 'Children'. $this->systemId .'Browse';
			$parentsWidget['widget'] = [
					'class' => $detailListClassName,
					'icon' => $this->icon, 
					'title' => '%%relationship%% %%type.'. $this->systemId .'.title.upperPlural%%'
				];
			$parentsWidget['locations'] = array('parent_objects');
			$parentsWidget['displayPriority'] = $this->priority + 1;
			$widgets[$id] = $parentsWidget;
		} else {
			Yii::trace("Warning: There is no browse class for the parent objects of {$this->systemId}");
		}
		return $widgets;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function taxonomies() {
		return array();
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function roles() {
		return array();
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function dependencies() {
		return array();
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function parents() {
		return array();
	}


	/**
	 * Settings for 
	 *
	 * @return unknown
	 */
	public function parentSettings() {
		return array(
			'title' => false,
			'allow' => 1, // 0/false = no; 1 = only 1; 2 = 1 or more
			'showDescriptor' => false
		);
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function childrenSettings() {
		return array(
			'allow' => 2,  // 0/false = no; 1 = only 1; 2 = 1 or more
		);
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function children() {
		return array();
	}



	public function getDummyModel() {
		if (!$this->primaryModel) { return false; }
		$model = $this->primaryModel;
		return new $model;
	}

	public function getIsChildless() {
		if (empty($this->collectorItem) OR empty($this->collectorItem->children)) {
			return true;
		}
		return false;
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getModel($primaryModel = null) {
		if (is_null($primaryModel)) {
			$primaryModel = new $this->primaryModel;
		}
		if (!$primaryModel) { return false; }
		return $primaryModel;
	}


	/**
	 *
	 *
	 * @param unknown $models (optional)
	 * @return unknown
	 */
	public function saveModels($models = null) {
		if (is_null($models)) {
			$models = $this->getModel();
		}
		foreach ($models as $key => $p) {
			if ($key === 'primary') {
				if (!$p['model']->save()) {
					return false;
				} else {
					if (!empty($p['parents'])) {
						$result = $this->recursiveSave($p['model'], $p['parents'], $p);
						if ($result === false) {
							return false;
						}
					}
					if (!empty($p['children'])) {
						$result = $this->recursiveSave($p['model'], $p['children'], $p);
						if ($result === false) {
							return false;
						}
					}
					if (!empty($p['childModels'])) {
						$result = $this->recursiveSave($p['model'], $p['childModels'], $p);
						if ($result === false) {
							return false;
						}
					}
					return true;
				}
			} else {
				// @todo is there ever going to be multiple at this level?
			}
		}
	}


	/**
	 *
	 *
	 * @param unknown $parent
	 * @param unknown $children
	 * @param unknown $parentOptions
	 * @param unknown $saveParent    (optional)
	 * @return unknown
	 */
	public function recursiveSave($parent, $children, $parentOptions, $saveParent = false) {
		foreach ($children as $key => $child) {
			if ($child['model'] === false) {
				continue;
			}
			if (!empty($child['parents'])) {
				if (!$this->recursiveSave($child['model'], $child['parents'], $child)) {
					return false;
				}
			}
			if (!empty($child['children'])) {
				if (!$this->recursiveSave($child['model'], $child['children'], $child)) {
					return false;
				}
			}
			if (!empty($child['childModels'])) {
				if (!$this->recursiveSave($child['model'], $child['childModels'], $child)) {
					return false;
				}
			}
			if (!empty($child['defaults'])) {
				foreach ($child['defaults'] as $k => $v) {
					$child['model']->{$k} = $v;
				}
			}
			if (isset($child['parentKey'])) {
				$child['model']->{$child['parentKey']} = $parent->id;
			}
			if (!empty($child['model']->deleteOnSave)) {
				if (!$child['model']->isNewRecord) {
					if (!$child['model']->delete()) {
						return false;
					}
				}

				if ($parent->hasAttribute($key)) {
					$parent->{$key} = null;
					$saveParent = true;
				}
			} else {
				if (!empty($child['ignoreInvalid']) and !$child['model']->validate()) {
					// nada
				}elseif (!$child['model']->save()) {
					return false;
				}
				if ($parent->hasAttribute($key)) {
					$parent->{$key} = $child['model']->id;
					$saveParent = true;
				}
			}
		}
		if ($saveParent && !empty($parent->id)) {
			if (!empty($parentOptions['ignoreInvalid']) and !$parent->validate()) {
				return true;
			}
			return $parent->save();
		}
		return true;
	}


	/**
	 *
	 *
	 * @param unknown $primaryModel (optional)
	 * @return unknown
	 */
	public function getForm($primaryModel = null, $settings = []) {
		$formSegments = [$this->getFormSegment($primaryModel, $settings)];
		return new FormGenerator($formSegments);
	}

	public function getFormSegment($primaryModel = null, $settings = [])
	{
		if (is_array($primaryModel)) {
			$models = $primaryModel;
		} else {
			if (is_null($primaryModel)) {
				$primaryModel = new $this->primaryModel;
			}
			$models = $this->getModel($primaryModel);
		}
		if (empty($primaryModel)) {
			return false;
		}
		return $primaryModel->form('primary', $settings);
	}
}


?>
