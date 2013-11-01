<?php

namespace app\modules\TypeAccount;

use Yii;

class Module extends \app\components\types\Module
{
	public $title = 'Account';
	public $icon = 'ic-icon-organization';
	public $uniparental = false;
	public $selfManaged = true;

	public $widgetNamespace = 'app\modules\TypeAccount\widgets';
	public $modelNamespace = 'app\modules\TypeAccount\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/TypeAccount/migrations');
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
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function children()
	{
		return [
			'Account' => ['uniqueChild' => true],
			'Individual' => ['uniqueChild' => true],
			'PhoneNumber' => ['uniqueChild' => true],
			'PostalAddress' => ['uniqueChild' => true],
			'WebAddress' => ['uniqueChild' => true],
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function taxonomies()
	{
		return [];
	}
}
