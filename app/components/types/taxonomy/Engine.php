<?php
/**
 * ./app/components/objects/taxonomy/RTaxonomyEngine.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\types\taxonomy;

use Yii;
use TypeItem;

use \infinite\base\exceptions\Exception;

class Engine extends \infinite\base\Engine {
	const MODEL = 'Taxonomy';
	const TYPE_MODEL = 'TaxonomyType';
	const EVENT_AFTER_TAXONOMY_REGISTRY = 'afterTaxonomyRegistry';

	protected $_registry = array();
	protected $_registryById = array();
	protected $_registryByModel = array();
	public $initial = array();

	/**
	 *
	 */
	public function init() {
		$this->register(Yii::$app->controller, $this->initial);
		return parent::init();
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function beforeRequest() {
		Yii::beginProfile('ModuleLoad');
		foreach (Yii::$app->modules as $module => $settings) {
			if (preg_match('/^TaxonomyType/', $module) === 0) { continue; }
			$mod = Yii::$app->getModule($module);
			$mod->init();
		}
		$this->trigger(self::EVENT_AFTER_TAXONOMY_REGISTRY);
		Yii::endProfile('ModuleLoad');
		return true;
	}

	public function getTypes() {
		return $this->_registryById;
	}


	/**
	 *
	 *
	 * @param unknown $id (optional)
	 * @return unknown
	 */
	public function getModel($id) {
		if (isset($this->_registryByModel[$id])) {
			return $this->_registryByModel[$id];
		}
		return false;
	}

	/**
	 *
	 *
	 * @param unknown $systemId (optional)
	 * @return unknown
	 */
	public function get($systemId) {
		if (isset($this->_registry[$systemId])) {
			return $this->_registry[$systemId];
		}
		return false;
	}


	/**
	 *
	 *
	 * @param unknown $id (optional)
	 * @return unknown
	 */
	public function getByPk($id) {
		if (isset($this->_registryById[$id])) {
			return $this->_registryById[$id];
		}
		return false;
	}



	/**
	 *
	 *
	 * @param unknown $owner
	 * @param unknown $taxonomies
	 * @return unknown
	 */
	public function register($owner, $taxonomies) {
		if (empty($taxonomies)) {
			return true;
		}
		foreach ($taxonomies as $taxonomy) {
			if (!isset($taxonomy['systemVersion'])) {
				$taxonomy['systemVersion'] = null;
			}
			if (isset($this->_registry[$taxonomy['systemId']]) AND $this->_registry[$taxonomy['systemId']]->systemVersion !== $taxonomy['systemVersion']) {
				throw new Exception("{$taxonomy['systemId']} has already been registered by ". get_class($this->_registry[$taxonomy['name']]->Owner) ." and there is a version mismatch ". $this->_registry[$taxonomy['name']]->systemVersion ." v {$taxonomy['systemVersion']}.");
			}
			if (isset($this->_registry[$taxonomy['systemId']])) {
				continue;
			}
			
			$tObject = new TypeItem($owner, $taxonomy);
			if (empty($tObject) or empty($tObject->id)) {
				continue;
			}
			$this->_registry[$taxonomy['systemId']] = $tObject;
			$this->_registryById[$tObject->id] = $tObject;
			if (isset($taxonomy['forModel'])) {
				if (!isset($this->_registryByModel[$taxonomy['forModel']])) {
					$this->_registryByModel[$taxonomy['forModel']] = array();
				}
				$this->_registryByModel[$taxonomy['forModel']][] = $tObject;
			}
		}
		return true;
	}



}


?>
