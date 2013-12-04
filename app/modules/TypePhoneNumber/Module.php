<?php

namespace app\modules\TypePhoneNumber;

use Yii;

class Module extends \app\components\types\Module
{
	protected $_title = 'Phone Number';
	public $icon = 'fa fa-phone';
	public $uniparental = true;
	public $hasDashboard = false;

	public $widgetNamespace = 'app\modules\TypePhoneNumber\widgets';
	public $modelNamespace = 'app\modules\TypePhoneNumber\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/TypePhoneNumber/migrations');
	}

	/**
	 * @inheritdoc
	 */
	public function widgets()
	{
		$widgets = parent::widgets();
		$widgets['EmbeddedPhoneNumberBrowse']['section'] = Yii::$app->collectors['sections']->getOne('_side');
		return $widgets;
	}

	
	/**
	 * @inheritdoc
	 */
	public function parents()
	{
		return [
			'Account' => [],
			'Individual' => [],
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function children()
	{
		return [];
	}

	
	/**
	 * @inheritdoc
	 */
	public function taxonomies()
	{
		return [];
	}
}