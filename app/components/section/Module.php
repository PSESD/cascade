<?php
/**
 * ./app/components/sections/RSectionModule.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\section;

use Yii;

use \infinite\base\language\Noun;
use \infinite\base\exceptions\Exception;
use \infinite\base\exceptions\HttpException;
use \infinite\helpers\ArrayHelper;
use \app\components\helpers\StringHelper;

class Module extends \app\components\base\CollectorModule {
	protected $_title;
	public $version = 1;

	public $objectSubInfo = array();
	public $icon = 'ic-icon-info';
	public $priority = 1000; //lower is better

	protected $_items;
	protected $_sectionTitle;


	public function getModuleType() {
		return 'Section';
	}

	public function getCollectorName() {
		return 'sections';
	}

	/**
	 *
	 *
	 * @param unknown $parent
	 * @param unknown $item
	 * @param unknown $settings (optional)
	 */
	public function addItem($parent, $item, $settings = array()) {
		$parentKey = $parent->name;
		if (!isset($this->_items[$parentKey])) {
			$this->_items[$parentKey] = $this->defaultItems($parent);
		}
		$this->_items[$parentKey][] =  new Item($item, $settings);
	}


	/**
	 *
	 *
	 * @param unknown $parent
	 * @return unknown
	 */
	public function getItems($parent) {
		$parentKey = $parent->name;
		if (!isset($this->_items[$parentKey])) {
			$this->_items[$parentKey] = $this->defaultItems($parent);
		}
		ArrayHelper::multisort($this->_items[$parentKey], 'displayPriority');
		return $this->_items[$parentKey];
	}


	/**
	 *
	 *
	 * @param unknown $parent (optional)
	 * @return unknown
	 */
	protected function defaultItems($parent = null) {
		return array();
	}

	public function setTitle($value) {
		$this->_title = $value;
	}

	public function getSectionTitle() {
		return StringHelper::parseText($this->_title);
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getTitle() {
		if (is_object($this->sectionTitle)) { return $this->sectionTitle; }
		return new Noun($this->sectionTitle);
	}


	/**
	 *
	 *
	 * @param unknown $controller
	 * @param unknown $action
	 * @return unknown
	 */
	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			if (!isset($_SERVER['PASS_THRU']) or $_SERVER['PASS_THRU'] != md5(Yii::$app->params['salt'] . 'PASS')) {
				throw new HttpException(400, 'Invalid request!');
			}
			return true;
		} else {
			return false;
		}
	}



}


?>
