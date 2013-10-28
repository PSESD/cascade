<?php
/**
 * ./app/components/web/widgets/core/RDashboardWatchWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\widgets\core;


class Watch extends \cascade\components\web\widgets\base\Widget {
	public $type_id;

	/**
	 *
	 */
	public function run() {
		$this->widgetTag = 'li';
		$this->grid = true;
		$this->gridCellSize = 'double';
		$this->gridCellHighlight = true;
		$this->gridTitle = Yii::t('ic', 'Watching');
		$this->gridTitleUrl = array('/settings/watch');
		$this->gridTitleTitle = 'Items you\'re watching';
		$this->gridTitleIcon = 'ic-icon-eye';
		$this->gridTitleMenu = array(
			array('url' => array('/settings/watching'), 'icon' => 'ic-icon-cog', 'title' => 'Check out more of what you are watching')
		);

		$this->prepare('widget');
	}


}


?>
