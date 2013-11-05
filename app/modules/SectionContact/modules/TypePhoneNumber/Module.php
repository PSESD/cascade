<?php

namespace app\modules\SectionContact\modules\TypePhoneNumber;

use Yii;

class Module extends \app\components\types\Module
{
	protected $_title = 'Phone Number';
	public $icon = 'ic-icon-iphone';
	public $uniparental = true;
	public $selfManaged = false;

	public $widgetNamespace = 'app\modules\SectionContact\modules\TypePhoneNumber\widgets';
	public $modelNamespace = 'app\modules\SectionContact\modules\TypePhoneNumber\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/SectionContact/modules/TypePhoneNumber/migrations');
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
