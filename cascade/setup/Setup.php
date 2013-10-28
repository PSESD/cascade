<?php
namespace app\setup;
use Yii;
class Setup extends \infinite\setup\Setup {
	
	public static function createSetupApplication($config = array()) {
		if (is_null(self::$_instance)) {
			$className = __CLASS__;
			self::$_instance = new $className($config);
		}
		return parent::createSetupApplication($config);
	}

}
?>