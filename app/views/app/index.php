<?php
/**
 * @var yii\base\View $this
 */
use \infinite\helpers\Html;
use \infinite\helpers\ArrayHelper;

$this->title = 'Dashboard';

echo Html::beginTag('div', ['class' => 'row']);
$widgets = Yii::$app->collectors['widgets']->getLocation('child_objects');
ArrayHelper::multisort($widgets, ['displayPriority', 'name'], [true, false]);
foreach ($widgets as $item => $widget) {
	$rendered = Yii::$app->collectors['widgets']->build($widget, array(), array());
	$widgetId = Yii::$app->collectors['widgets']->lastBuildId;
	echo $rendered;
}
echo Html::endTag('div');
?>