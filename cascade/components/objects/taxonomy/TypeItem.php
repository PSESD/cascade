<?php
/**
 * ./app/components/objects/taxonomy/RTaxonomyTypeItem.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\objects\taxonomy;

use Yii;
use Engine;

use \infinite\base\exceptions\Exception;
use \infinite\helpers\ArrayHelper;

class TypeItem extends \infinite\base\Component {
	public $Owner;
	public $name;
	public $id;
	public $systemId;
	public $systemVersion = 1;
	public $initialTaxonomies = array();
	public $model;
	public $forModel;
	public $multiple = false;
	public $required = false;
	public $default = array();
	public $parentUnique = false;

	protected $_taxonomies;

	/**
	 *
	 *
	 * @param unknown $owner
	 * @param unknown $settings
	 * @return unknown
	 */
	public function __construct($owner, $settings) {
		$this->Owner = $owner;
		foreach ($settings as $k => $v) {
			$this->{$k} = $v;
		}

		if (!isset($this->name) or !isset($this->systemId)) {
			throw new Exception("Attempted taxonomy registration for ". get_class($owner) ." without proper setup.");
		}

		$_ttModel = Engine::TYPE_MODEL;
		$this->model = $_ttModel::getSystemType($this->systemId);
		if (empty($this->model)) {
			$this->model = new $_ttModel;
			$this->model->name = $this->name;
			$this->model->system_id = $this->systemId;
			$this->model->system_version = $this->systemVersion;
			if (!$this->model->save()) {
				throw new Exception("Couldn't save new taxonomy type {$this->systemId}");
			}
			Yii::trace("New taxonomy type has been registered {$this->name} ({$this->systemId})");
			if ($this->initType()) {
				Yii::trace("Taxonomy type has been initialized {$this->name} ({$this->systemId})");
			} else {
				throw new Exception("Couldn't initialize taxonomy type {$this->systemId}");
			}
		}
		$this->id = $this->model->id;
		if ($this->systemVersion > $this->model->system_version) {
			if ($this->initType()) {
				$this->model->system_version = $this->systemVersion;
				if (!$this->model->save()) {
					throw new Exception("Couldn't save new taxonomy type {$this->systemId} with new version");
				}
				Yii::trace("Taxonomy type has been upgraded {$this->name} ({$this->systemId}) to version {$this->systemVersion}");
			} else {
				throw new Exception("Couldn't upgrade taxonomy type {$this->systemId} to version {$this->systemVersion}");
			}
		}
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function initType() {
		$_tModel = Engine::MODEL;
		foreach ($this->initialTaxonomies as $systemId => $name) {
			$type = $_tModel::find()->where(array('taxonomy_type_id' => $this->model->id, 'system_id' => $systemId))->one();
			if (empty($type)) {
				$type = new $_tModel;
				$type->taxonomy_type_id = $this->model->id;
				$type->name = $name;
				$type->system_id = $systemId;
				if (!$type->save()) {
					return false;
				}
			}
		}
		return true;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getTaxonomies() {
		if (is_null($this->_taxonomies)) {
			$this->_taxonomies = $this->model->taxonomies;
		}
		return $this->_taxonomies;
	}

	public function addTaxonomy($taxonomy) {
		$this->taxonomies;
		if (!is_array($this->_taxonomies)) {
			$this->_taxonomies = array();
		}
		$this->_taxonomies[] = $taxonomy;
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getTaxonomy($system_id) {
		foreach ($this->getTaxonomies() as $taxonomy) {
			if ($taxonomy->system_id === $system_id) {
				return $taxonomy;
			}
		}
		return false;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getTaxonomyList() {
		$taxonomiesRaw = $this->getTaxonomies();
		return ArrayHelper::map($this->getTaxonomies(), 'id', 'name');
	}


}


?>
