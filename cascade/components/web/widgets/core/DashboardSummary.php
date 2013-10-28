<?php
/**
 * ./app/components/web/widgets/RDashboardSummaryWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace cascade\components\web\widgets\core;

use Yii;

use \cascade\models\Registry;
use \cascade\models\ObjectFamiliarity;

use \infinite\web\Response;
use \infinite\helpers\Html;
use \infinite\db\behaviors\Relatable;
use \infinite\db\behaviors\Access;

class DashboardSummary extends \cascade\components\web\widgets\base\Widget {
	public $objectId;

	protected $_gridCellSize = 'single half-height';

	/**
	 *
	 */
	public function run() {
		$response = new Response('index', array(), $this);
		$this->widgetTag = 'li';
		$this->grid = true;
		$this->gridTitle = Yii::t('ic', $this->Owner->title->getPlural(true));
		$this->gridTitleUrl = array('/app/browse', 'module' => $this->Owner->shortName);
		$this->gridTitleTitle = Yii::t('ic', 'Browse '. $this->Owner->title->getPlural(true));

		$this->gridTitleMenu = array();
		if (Yii::app()->gk->canGeneral('create', $this->Owner->primaryModel)) {
			$this->gridTitleMenu[] = array('url' => array('/app/create', 'module' => $this->Owner->shortName), 'icon' => 'ic-icon-plus', 'ajax' => true, 'title' => Yii::t('ic', 'Create a '. $this->Owner->title->getSingular(true)));
		}

		$this->params['items'] = $this->getItems();
		$response->handlePartial();
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getItems() {
		return ObjectFamiliarity::familiarObjectsProvider($this->Owner->primaryModel, $this->state);
	}


}


?>
