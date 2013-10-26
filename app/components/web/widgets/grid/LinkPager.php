<?php
/**
 * ./app/components/web/widgets/core/RLinkPager.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


class RLinkPager extends CLinkPager {
	protected $_state;

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getState() {
		return $this->_state;
	}


	/**
	 *
	 *
	 * @param unknown $state
	 */
	public function setState($state) {
		$this->_state = $state;
	}


	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 *
	 * @param string  $label    the text label for the button
	 * @param integer $page     the page number
	 * @param string  $class    the CSS class for the page button.
	 * @param boolean $hidden   whether this page button is visible
	 * @param boolean $selected whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label, $page, $class, $hidden, $selected) {
		if ($hidden || $selected) {
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		}
		$state = array();
		$state['page'] = $page + 1;
		$jsonState = json_encode($state);
		return '<li class="'.$class.'">'.CHtml::link($label, $this->createPageUrl($page), array('data-state' => $jsonState, 'class' => 'stateful')).'</li>';
	}


}


?>
