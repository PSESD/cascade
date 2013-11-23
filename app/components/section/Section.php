<?php
/**
 * ./app/components/sections/RSectionModule.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\section;

use Yii;

class Section extends \infinite\base\Object {
	use SectionTrait;
	protected $_systemId;
	
	/**
	 *
	 *
	 * @param unknown $value
	 */
	public function setSystemId($value) {
		$this->_systemId = $value;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSystemId() {
		return $this->_systemId;
	}
}