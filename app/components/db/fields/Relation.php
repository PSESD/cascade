<?php
/**
 * ./app/components/objects/fields/Model.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\db\fields;


class Relation extends Base {
	public $formFieldClass = '\app\components\web\form\fields\Relation';
	protected $_human = true;
	protected $_moduleHandler;
	public $relationship;
	public $modelRole; // either parent or child
	static $_moduleHandlers = [];

	public function getCompanion() {
		if ($this->modelRole === 'parent') {
			return $this->relationship->child;
		} else {
			return $this->relationship->parent;
		}
	}

	public function getModuleHandler() {
		if (is_null($this->_moduleHandler)) {
			$stem = $this->field;
			if (!isset(self::$_moduleHandlers[$stem])) { self::$_moduleHandlers[$stem] = []; }
			$n = count(self::$_moduleHandlers[$stem]);
			$this->_moduleHandler = $this->field .':'. $n;
			self::$_moduleHandlers[$stem][] = $this->_moduleHandler;
		}
		return $this->_moduleHandler;
	}

	public function getCompanionModel() {
		
	}
}

?>