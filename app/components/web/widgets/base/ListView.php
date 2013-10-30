<?php
/**
 * ./app/components/web/widgets/core/RBaseListView.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\base;

abstract class ListView extends \yii\widgets\ListView {
	public $pager = '\app\components\web\widgets\grid\LinkPager';
	public $currentStyle;
	public $currentFilter;
	public $styles = false;
	public $filters = false;

	public function renderStyles() {
		if (empty($this->styles)) {
			return true;
		}

		echo '<ul class="view-style-select">';
		$name = 'list-view-style-'. self::baseClassName() .'';
		foreach ($this->styles as $key => $style) {
			echo '<li>'.$this->renderStyleOption($style['label'], $key, $style['icon']) .'</li>';
			$id = $name . '-'. $key;
		}
		echo '</ul>';
	}

	public function renderStyleOption($label, $option, $icon) {
		$state = array();
		$state['viewStyle'] = $option;
		$jsonState = json_encode($state);
		$classes = 'stateful ic-icon ic-icon-20 ic-icon-blue '. $icon;
		if ($option === $this->currentStyle) {
			$classes .= ' ui-state-active';
		}
		return RHtml::link("", '#', array('data-state' => $jsonState, 'title' => $label, 'class' => $classes));
	}

}


?>
