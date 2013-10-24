<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<h1><?=Html::encode($this->title); ?></h1>

	<?php $form = ActiveForm::begin([
		'id' => 'login-form',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-1 control-label'],
		],
	]); ?>

	<?=$form->field($model, 'username'); ?>

	<?=$form->field($model, 'password')->passwordInput(); ?>

	<?=$form->field($model, 'rememberMe', [
		'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
	])->checkbox(); ?>

	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?=Html::submitButton('Login', ['class' => 'btn btn-primary']); ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>
</div>
