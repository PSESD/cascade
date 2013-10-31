<?php
/**
 * @var yii\base\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yii\gii\generators\module\Generator $generator
 */
	echo \yii\helpers\Html::activeHiddenInput($generator, 'migrationTimestamp');
?>
<div class="module-form">
<?php

//	echo $form->field($generator, 'moduleName');

//	echo $form->field($generator, 'moduleClass');
//	echo $form->field($generator, 'moduleID');

	echo $form->field($generator, 'baseNamespace');
	echo $form->field($generator, 'tableName');
	
	echo $form->field($generator, 'children');
	echo $form->field($generator, 'parents');

//	echo $form->field($generator, 'modelClass');
//	echo $form->field($generator, 'ns');
//	echo $form->field($generator, 'baseClass');
//	echo $form->field($generator, 'db');
//	echo $form->field($generator, 'generateRelations')->checkbox();
//	echo $form->field($generator, 'generateLabelsFromComments')->checkbox();

?>
</div>
