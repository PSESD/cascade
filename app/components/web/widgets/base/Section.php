<?php
namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\Html;

class Section extends Widget {
	public $gridClass = 'infinite\web\grid\Grid';
	public $section;

	public function init()
	{
		parent::init();
		if (isset($this->section)) {
			$this->icon = $this->section->icon;
			$this->title = $this->section->sectionTitle;
		}
	}

	public function generateHeader()
	{
		$this->htmlOptions['id'] = 'section-'.$this->section->systemId;
		if ($this->isSingle) {
			$parts = [];
			Html::addCssClass($this->htmlOptions, 'single-section');
			$parts[] = Html::beginTag('div', $this->htmlOptions);
			return implode("", $parts);
		}
		return parent::generateHeader();
	}


	public function generateFooter()
	{
		if ($this->isSingle) {
			$parts = [];
			$parts[] = Html::endTag('div');
			return implode("", $parts);
		}
		return parent::generateFooter();
	}

	public function generateContent()
	{
		$items = [];
		foreach ($this->widgets as $widget) {
			$items[] = $cell = Yii::$app->collectors['widgets']->build($widget->object);
			if ($this->isSingle) {
				$cell->columns = 12;
			}
		}
		$grid = Yii::createObject(['class' => $this->gridClass, 'cells' => $items]);
		$extra = "<pre>Fingerstache distillery fixie, ethical cardigan keytar raw denim meggings occupy. Forage scenester yr brunch, iPhone +1 next level cred banjo cornhole Pitchfork ugh distillery Vice. Synth irony gluten-free Wes Anderson selfies. Cray Williamsburg typewriter squid single-origin coffee mumblecore. Fanny pack artisan salvia, Austin umami +1 farm-to-table seitan sartorial. Master cleanse Williamsburg PBR, distillery wolf narwhal try-hard readymade. Gluten-free Tonx salvia flexitarian you probably haven't heard of them, XOXO Carles fashion axe yr banjo messenger bag chillwave.

Tousled kitsch semiotics ethnic master cleanse, hoodie locavore mixtape mustache bespoke direct trade flexitarian gentrify Truffaut mlkshk. Church-key Portland gluten-free pickled pork belly, literally roof party. Brunch Etsy before they sold out put a bird on it. Blog wayfarers umami flannel, meh bicycle rights post-ironic vinyl church-key direct trade leggings fashion axe asymmetrical four loko master cleanse. Try-hard Portland Truffaut iPhone Marfa dreamcatcher, bitters photo booth Pinterest. Chambray synth fashion axe gastropub, PBR&B dreamcatcher small batch McSweeney's butcher four loko. Fap paleo next level, master cleanse Neutra mixtape Bushwick ennui irony pug.

Meggings master cleanse Williamsburg, Odd Future cardigan fanny pack XOXO Carles readymade seitan shabby chic craft beer meh butcher aesthetic. Banksy meggings Williamsburg, Brooklyn squid street art occupy selvage craft beer whatever before they sold out YOLO iPhone. Literally Neutra four loko craft beer. Bespoke lomo slow-carb pour-over. Leggings tote bag ethical Austin try-hard, sriracha sartorial craft beer gastropub literally vinyl readymade biodiesel chillwave. Authentic PBR&B YOLO meh, next level Truffaut Pinterest photo booth sartorial viral hashtag. Disrupt slow-carb raw denim, leggings pork belly Bushwick banh mi Truffaut iPhone pop-up distillery squid single-origin coffee Neutra.

Hella occupy Cosby sweater, Brooklyn +1 Etsy actually retro. Actually cliche 8-bit brunch, dreamcatcher +1 salvia Odd Future. Fanny pack butcher Neutra distillery master cleanse. YOLO Terry Richardson sriracha shabby chic. Selvage chillwave Marfa pour-over mixtape, readymade vinyl quinoa. Blog drinking vinegar letterpress Blue Bottle mixtape, fanny pack vegan. DIY farm-to-table retro fap, literally next level direct trade keytar paleo PBR selfies raw denim flannel.</pre>";
		return $grid->generate() . $extra;
	}

	public function getIsSingle() {
		return count($this->widgets) === 1;
	}

	public function getWidgets() {
		return $this->section->getAll();;
	}
}
?>