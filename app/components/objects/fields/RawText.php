<?php
/**
 * ./app/components/objects/fields/RawText.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\objects\fields;

class RawText extends BaseFormat {
	public function get() {
		return $this->_field->value;
	}
}


?>
