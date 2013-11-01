<?php

namespace app\modules\TypePhoneNumber;

use Yii;

class Module extends \app\components\types\Module
{
	public $title = 'Phone Number';
	public $icon = 'ic-icon-iphone';
	public $uniparental = true;
	public $selfManaged = false;

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
		return parent::widgets();
	}

	
	/**
	 * @inheritdoc
	 */
	public function parentSettings()
	{
		$settings = parent::parentSettings();
		$settings['title'] = false;
		$settings['showDescriptor'] = true;
		$settings['allow'] = 2;
		return $settings;
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
		return [		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function taxonomies()
	{
		return [];
	}
}
