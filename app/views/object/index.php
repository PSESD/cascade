<?php
use Yii;

use \infinite\helpers\Html;
use \infinite\helpers\ArrayHelper;
use \infinite\web\grid\Grid;

$this->title = 'Dashboard';
echo Html::beginTag('div', ['class' => 'row']);
$widgets = Yii::$app->collectors['widgets']->getLocation('front');
var_dump(count($widgets));
ArrayHelper::multisort($widgets, ['displayPriority', 'name'], [true, false]);
Yii::beginProfile("Building Grid");
$grid = new Grid;
$cells = [];
foreach ($widgets as $item => $widget) {
	$cells[] = Yii::$app->collectors['widgets']->build($widget, array(), array());
}
$grid->addCells($cells);
echo $grid->generate();
Yii::endProfile("Building Grid");
echo Html::endTag('div');
?>