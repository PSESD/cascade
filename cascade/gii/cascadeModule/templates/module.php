<?php
/**
 * This is the template for generating a module class file.
 *
 * @var yii\base\View $this
 * @var yii\gii\generators\module\Generator $generator
 */
$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>

namespace <?=$ns; ?>;


class <?=$className; ?> extends \app\components\objects\Module
{
	public $widgetNamespace = '<?=$generator->getWidgetNamespace(); ?>';
	public $modelNamespace = '<?=$generator->getModelNamespace(); ?>';

	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}

	public function widgets()
	{
		return parent::widgets();
	}

	/**
	 * Settings for parent relationship manager
	 *
	 * @return array settings for parent relationship manager
	 */
	public function parentSettings()
	{
		$settings = parent::parentSettings();
		$settings['title'] = false;
		$settings['showDescriptor'] = true;
		$settings['allow'] = <?php if ($generator->independent) { echo '2'; } else { echo '1'; } ?>;
		return $settings;
	}

	/**
	 *	List of parents for initialization; Use $this->objectType->parents for actual list of parents
	 *
	 * @return array of parent object types
	 */
	public function parents()
	{
		return array(<?php
			if (!empty($generator->parents)) {
				echo "\n";
				foreach(explode(',', $generator->parents) as $parent) {
					$parent = trim($parent);
					echo "\t\t\t'{$parent}' => array(),\n";
				}
			}
		?>);
	}

	/**
	 *	List of children for initialization; Use $this->objectType->children for actual list of children
	 *
	 * @return array of child object types
	 */
	public function children()
	{
		return array(<?php
			if (!empty($generator->children)) {
				echo "\n";
				foreach(explode(',', $generator->children) as $child) {
					$child = trim($child);
					echo "\t\t\t'{$child}' => array('uniqueChild' => true),\n";
				}
			}
		?>);
	}

	/**
	 *	Set up taxonomies for this module
	 *
	 * @return array of taxonomies
	 */
	public function taxonomies()
	{
		return array();
	}
}
