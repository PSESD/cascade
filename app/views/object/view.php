<?php
use infinite\helpers\Html;
use yii\bootstrap\Nav;
use infinite\web\bootstrap\SubNavBar;

$js = [];
$js[] = "\$('body').scrollspy({ target: '#object-dashboard-navbar', 'offset': 100 });";
echo Html::beginTag('div', ['class' => 'dashboard']);
$navBar = SubNavBar::begin([
	'brandLabel' => $object->descriptor,
	'brandUrl' => $object->getUrl('view'),
	'options' => [
		'class' => 'navbar-fixed-top',
	],
]);
$sectionsMenu = [];
foreach ($sections as $section) {
	// var_dump($section->object);exit;
	$sectionsMenu[] = ['label' => $section->object->sectionTitle, 'url' => '#section-'.$section->systemId];
}
echo Html::beginTag('div', ['id' => 'object-dashboard-navbar']);
echo Nav::widget([
	'options' => ['class' => 'navbar-nav pull-right'],
	'items' => $sectionsMenu,
]);
echo Html::endTag('div');
SubNavBar::end();

foreach ($sections as $section) {
	$section->object->render();
}

echo Html::endTag('div'); // .dashboard
$this->registerJs(implode("\n", $js));
?>