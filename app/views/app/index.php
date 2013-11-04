<?php
/**
 * @var yii\base\View $this
 */
use \infinite\helpers\Html;

$this->title = 'Dashboard';

echo Html::beginTag('div', ['class' => 'row']);
$widgets = Yii::$app->collectors['widgets']->getLocation('child_objects');

echo Html::endTag('div');
?>