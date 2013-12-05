<?php
namespace cascade\components\web\form;

use infinite\helpers\Html;

class ActiveForm extends \yii\widgets\ActiveForm {
	public static function begin($config = [], $echo = true)
	{
		ob_start();
		ob_implicit_flush(false);
		$return = parent::begin($config);
		$result = ob_get_clean();
		if (!$echo) {
			return [$return, $result];
		}
		echo $result;
		return $return;
	}

	public static function end($echo = true)
	{
		ob_start();
		ob_implicit_flush(false);
		parent::end();
		$result = ob_get_clean();
		if (!$echo) {
			return $result;
		}
		echo $result;
	}
}
?>