<?php
/**
 * ./app/components/sections/RSectionItem.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */
namespace app\components\sections;

class Item extends \infinite\base\Object {
	protected $_item;
	protected $_settings = array();

	/**
	 *
	 *
	 * @param unknown $item
	 * @param unknown $settings (optional)
	 */
	public function __construct($item, $settings = array()) {
		$this->_settings = $settings;
		$this->_item = $item;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getDisplayPriority() {
		if (!isset($this->_settings['displayPriority'])) {
			return $this->_item->displayPriority;
		}
		return $this->_settings['displayPriority'];
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getKey() {
		return $this->_item->name .'-'. md5(serialize($this->_settings));
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getItem() {
		return $this->_item;
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSettings() {
		return $this->_settings;
	}


}


?>
