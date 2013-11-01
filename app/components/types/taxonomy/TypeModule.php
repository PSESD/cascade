<?php
namespace app\components\types\taxonomy;

use Yii;
use Engine;

use \yii\base\Event;

abstract class TypeModule extends \yii\base\Module {
	public $name;
	public $icon = 'ic-icon-info';
	public $priority = 1000;
	public $version = 1;

	/**
	 *
	 *
	 * @param unknown $id
	 * @param unknown $parent
	 * @param unknown $config (optional)
	 */
	function __construct($id, $parent, $config=null) {
		parent::__construct($id, $parent, $config);
		Yii::$app->taxonomyEngine->on(Engine::EVENT_AFTER_EVENT_REGISTRY, array($this, 'onBeginRequest'));
	}

	public function onBeginRequest(Event $event) {
	}

	abstract public function objectTypes();
	abstract public function initialTaxonomies($version = 1);
}
?>