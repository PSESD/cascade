<?php
/**
 * ./protected/components/page/RPageTypeRegistry.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\objects;

use Yii;
use Relationship;
use Module;
use Type;

use \cascade\models\ObjectModel;

use \infinite\base\exceptions\Exception;
use \infinite\base\helpers\ArrayHelper;

class Engine extends \infinite\base\Engine {
	const EVENT_AFTER_TYPE_REGISTRY = 'afterTypeRegistry';

	protected $_types = [];
	protected $_typesByModel = [];
	protected $_tableRegistry;

/**
	 *
	 *
	 * @return unknown
	 */
	public function beforeRequest() {
		Yii::beginProfile('ModuleLoad');
		foreach (Yii::$app->modules as $module => $settings) {
			if (preg_match('/^Object/', $module) === 0) { continue; }
			$mod = Yii::$app->getModule($module);
		}
		if ( !(defined('INFINITE_APP_SETUP') && INFINITE_APP_SETUP) ) {
			foreach ($this->_types as $type) {
				$mod = $type->module;
				$shortName = $mod->shortName;
				// Database module registry
				$this->registerObjectModel($mod);

				if (!isset($this->tableRegistry[$shortName])) {
					throw new Exception("Unable to initialize module $shortName");
				}

				if ($this->tableRegistry[$shortName]->system_version < $mod->objectVersion) {
					$oldVersion = $this->_tableRegistry[$shortName]->system_version;
					$this->_tableRegistry[$shortName]->system_version = $mod->objectVersion;
					if (!$mod->upgrade($oldVersion) OR !$this->_tableRegistry[$shortName]->save()) {
						throw new Exception("Unable to upgrade module $shortName to {$mod->objectVersion} from {$oldVersion}");
					}
				}
			}
		}
		$this->trigger(self::EVENT_AFTER_TYPE_REGISTRY);
		Yii::endProfile('ModuleLoad');
		return parent::beforeRequest();
	}

	/**
	 *
	 *
	 * @param unknown $parent
	 * @param unknown $child
	 * @param unknown $options (optional)
	 * @return unknown
	 */
	public function addRelationship($parent, $child, $options = array()) {
		$parentRef = $this->ref($parent);
		$childRef = $this->ref($child);
		$relationship = Relationship::get($parentRef, $childRef, $options);
		$parentRef->addChild($child, $relationship);
		$childRef->addParent($parent, $relationship);
		return true;
	}


	public function getTableRegistry() {
		if (is_null($this->_tableRegistry)) {
			$om = ObjectModel::find()->all();
			$this->_tableRegistry = ArrayHelper::map($om, 'name');
		}
		return $this->_tableRegistry;
	}

	/**
	 *
	 *
	 * @param object  $mod
	 * @return unknown
	 */
	public function add(Module $mod) {
		$shortName = $mod->shortName;
		if (!isset($this->_types[$shortName])) {
			$this->_types[$shortName] = new Type($shortName);
			$this->_types[$shortName]->module = $mod;
			Yii::trace("'{$shortName}' object type has been registered and loaded");
		} else {
			$this->_types[$shortName]->module = $mod;
			Yii::trace("'{$shortName}' object type has been loaded");
		}

		$this->_typesByModel[$mod->primaryModel] = $this->_types[$shortName];
		return $this->_types[$shortName];
	}


	/**
	 *
	 *
	 * @param unknown $mod
	 * @return unknown
	 */
	public function ref($mod) {
		if (!isset($this->_types[$mod])) {
			$this->_types[$mod] = new Type($mod);
			Yii::trace("'{$mod}' object type has been registered");
		}
		return $this->_types[$mod];
	}


	/**
	 *
	 *
	 * @param unknown $remove
	 * @return unknown
	 */
	public function remove($remove) {
		if (is_object($remove) and $remove instanceof Module) {
			$shortName = $remove->shortName;
			unset($this->_types[$shortName]);
			return true;
		} elseif (is_string($remove)) {
			unset($this->_types[$remove]);
			return true;
		}

		return false;
	}


	/**
	 *
	 *
	 * @param unknown $test
	 * @param unknown $activeOnly (optional)
	 * @return unknown
	 */
	public function has($test, $activeOnly = true) {
		if (is_object($test) and $test instanceof Module) {
			$shortName = $test->shortName;
			return isset($this->_types[$shortName]) && (!$activeOnly or $this->_types[$shortName]->active);
		} elseif (is_string($test)) {
			return isset($this->_types[$test]) && (!$activeOnly or $this->_types[$test]->active);
		}
		return false;
	}


	/**
	 *
	 *
	 * @param unknown $get        (optional)
	 * @param unknown $activeOnly (optional)
	 * @return unknown
	 */
	public function get($get = null, $activeOnly = true) {
		if ($get === null) {
			if ($activeOnly) {
				$types = array();
				foreach ($this->_types as $k => $m) {
					if (!$m->active) { continue; }
					$types[$k] = $m->module;
				}
				return $types;
			}
			return $this->_types;
		}
		if (isset($this->_types[$get]) and !($activeOnly and !$this->_types[$get]->active)) {
			return $this->_types[$get]->module;
		}
		return false;
	}

	public function getTopTypes($activeOnly = true) {
		$types = array();
		foreach ($this->_types as $k => $m) {
			if (!$m->active) { continue; }
			if ($m->getModule()->objectLevel !== 1) { continue; }
			$types[$k] = $m->module;
		}
		return $types;
	}

	public function getTopTypesList($activeOnly = true) {
		$typesRaw = $this->getTopTypes($activeOnly);
		$types = array();
		foreach ($typesRaw as $type) {
			$types[$type->shortName] = $type->title->getPlural(true);
		}
		asort($types);
		return $types;
	}

	/**
	 *
	 *
	 * @param unknown $get        (optional)
	 * @param unknown $activeOnly (optional)
	 * @return unknown
	 */
	public function getByModel($get = null, $activeOnly = true) {
		if ($get === null) {
			if ($activeOnly) {
				$types = array();
				foreach ($this->_typesByModel as $k => $m) {
					if (!$m->active) { continue; }
					$types[$k] = $m->module;
				}
				return $types;
			}
			return $this->_typesByModel;
		}
		if (isset($this->_typesByModel[$get]) and !($activeOnly and !$this->_typesByModel[$get]->active)) {
			return $this->_typesByModel[$get]->module;
		}
		return false;
	}


	/**
	 *
	 *
	 * @param unknown $model
	 * @return unknown
	 */
	public function refByModel($model) {
		if (!isset($this->_typesByModel[$model])) {
			return false;
		}
		return $this->_typesByModel[$model];
	}


	/**
	 *
	 *
	 * @param unknown $get
	 * @return unknown
	 */
	public function getParents($get) {
		if (!isset($this->_types[$get])) {
			return false;
		}
		$r = array();
		foreach ($this->_types[$get]->parents as $rel) {
			if (!$rel->active) { continue; }
			$r[] = $rel->parent;
		}
		return $r;
	}


	/**
	 *
	 *
	 * @param unknown $get
	 * @return unknown
	 */
	public function getChildren($get) {
		if (!isset($this->_types[$get])) {
			return false;
		}
		$r = array();
		foreach ($this->_types[$get]->children as $rel) {
			if (!$rel->active) { continue; }
			$r[] = $rel->child;
		}
		return $r;
	}


	

	public function registerObjectModel($mod) {
		$shortName = $mod->shortName;
		if ( (defined('INFINITE_APP_SETUP') AND INFINITE_APP_SETUP) ) {
			return true;
		}

		if (!isset($this->tableRegistry[$shortName])) {
			$this->_tableRegistry[$shortName] = new ObjectModel;
			$this->_tableRegistry[$shortName]->name = $shortName;
			$this->_tableRegistry[$shortName]->system_version = $mod->objectVersion;
			if (!$mod->setup() OR !$this->_tableRegistry[$shortName]->save()) {
				unset($this->_tableRegistry[$shortName]);
			}
		}
		return true;
	}
}


?>
