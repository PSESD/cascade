<?php
/**
 * ./app/components/objects/fields/FormatText.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\types\fields;


class FormatText extends BaseFormat {
	public function get() {
		$result = $this->_field->value;
		if (empty($result)) {
			$result = '<span class="empty">(none)</span>';
		}
		return $result;
	}
}


?>
