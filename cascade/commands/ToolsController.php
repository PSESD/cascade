<?php
namespace app\commands;
use \yii\db\Query;

class ToolsController extends \yii\console\Controller {
	public function actionIndex() {
		$m = \app\models\Relation::find(4);
		$m->active = 'bbbbbbbbb';
		var_dump($m->save());
		var_dump($m->start);

	}
}
?>