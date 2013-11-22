<?php
/**
 * ./app/components/objects/fields/Model.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\db\fields;

use \app\components\web\form\fields\Relation as RelationFormField;

class Relation extends Base {
	protected $_human = true;
	public $relationship;
	public $modelRole; // either parent or child

	public function getCompanion() {
		if ($this->modelRole === 'parent') {
			return $this->relationship->child;
		} else {
			return $this->relationship->parent;
		}
	}

	public function getCompanionModel() {
		
	}
	/**
	 * 
	 */
	public function setFormField($value) {
		if (is_array($value)) {
			$value = new RelationFormField($this, $value);
		}

		$this->_formField = $value;
		return true;
	}
}

?>