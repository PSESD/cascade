<?php

namespace app\modules\TypeIndividual;

use Yii;

class Module extends \app\components\types\Module
{
	protected $_title = 'Individual';
	public $icon = 'fa fa-user';
	public $uniparental = false;
	public $selfManaged = true;

	public $widgetNamespace = 'app\modules\TypeIndividual\widgets';
	public $modelNamespace = 'app\modules\TypeIndividual\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/TypeIndividual/migrations');
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
			'PostalAddress' => ['uniqueChild' => true],
			'EmailAddress' => ['uniqueChild' => true],
			'PhoneNumber' => ['uniqueChild' => true],
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