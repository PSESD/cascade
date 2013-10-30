<?php
/**
 * This is the template for generating a controller class within a module.
 *
 * @var yii\base\View $this
 * @var yii\gii\generators\module\Generator $generator
 */
echo "<?php\n";
?>

namespace <?=$generator->getWidgetNamespace(); ?>;

class Browse extends \app\components\objects\Module
{
	public $gridCellSize = 'full';
}
