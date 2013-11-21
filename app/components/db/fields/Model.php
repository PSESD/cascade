<?php
/**
 * ./app/components/objects/fields/Model.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\db\fields;


use \app\components\web\form\fields\Model as ModelFormField;

class Model extends Base {
	/**
	 *
	 *
	 * @param unknown $value
	 * @return unknown
	 */
	public function setFormField($value) {
		if (is_array($value)) {
			$value = new ModelFormField($this, $value);
		}

		$this->_formField = $value;
		return true;
	}


}


?>
