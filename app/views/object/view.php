<?php
use \infinite\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$js = [];
echo Html::beginTag('div', ['class' => 'dashboard']);
$navBar = NavBar::begin([
	'brandLabel' => $object->descriptor,
	'brandUrl' => $object->getUrl('view'),
	'options' => [
		'class' => 'navbar-fixed-top',
	],
]);
NavBar::end();
//$js[] = "$('#{$navBar->id}').affix();";
echo "<pre>";
echo <<< END
Slow-carb food truck disrupt Truffaut, roof party raw denim readymade wolf ennui stumptown church-key. You probably haven't heard of them Austin whatever Tumblr street art, blog kale chips. Irony Intelligentsia trust fund literally quinoa, kitsch swag whatever PBR&B. Chambray aesthetic fixie, American Apparel Carles actually Neutra vinyl four loko typewriter messenger bag post-ironic butcher viral kogi. Single-origin coffee vegan flexitarian keytar mustache, Cosby sweater before they sold out fap Blue Bottle trust fund ethnic American Apparel gluten-free selvage pork belly. Bushwick mlkshk bicycle rights flexitarian hoodie ugh. Literally Truffaut banjo, deep v gentrify banh mi Williamsburg direct trade Godard keytar +1 Thundercats shabby chic.

Jean shorts mustache occupy, scenester Carles vinyl hashtag ugh viral tote bag Bushwick literally. You probably haven't heard of them stumptown wolf, readymade master cleanse DIY scenester. Butcher stumptown mixtape pickled, XOXO Marfa chia dreamcatcher asymmetrical paleo Wes Anderson synth you probably haven't heard of them slow-carb swag. Fap Pitchfork Pinterest, authentic chia pork belly quinoa hoodie. Single-origin coffee craft beer fanny pack Schlitz cray, paleo Cosby sweater selfies whatever fingerstache raw denim. Brooklyn mumblecore forage meh. Food truck mlkshk mumblecore typewriter.

Vinyl cardigan gluten-free synth street art, beard freegan pop-up Vice Intelligentsia Etsy. Direct trade cray 3 wolf moon, Intelligentsia Neutra before they sold out YOLO bitters Tonx shabby chic. Literally authentic leggings four loko. Pinterest beard ethnic swag, meggings plaid PBR. Distillery Brooklyn swag, church-key kale chips retro gluten-free gentrify direct trade cliche kogi next level Neutra XOXO 8-bit. Intelligentsia yr authentic meh scenester Truffaut. Actually Pinterest Wes Anderson, DIY pug fixie selfies salvia vinyl beard meh art party polaroid.

Neutra pop-up distillery, street art Etsy Banksy Truffaut. Narwhal distillery tousled banjo. Dreamcatcher post-ironic chillwave 90's. Kogi retro small batch, VHS bitters dreamcatcher Portland food truck squid whatever asymmetrical salvia Thundercats Terry Richardson butcher. Keytar direct trade literally viral. Wolf Neutra gentrify flannel mumblecore. Gentrify butcher organic chillwave, Godard irony distillery mlkshk banjo bicycle rights drinking vinegar Tumblr gastropub put a bird on it.

Chambray gluten-free 8-bit butcher brunch pork belly. Literally brunch kitsch yr 8-bit, Helvetica gluten-free cardigan Blue Bottle lo-fi Intelligentsia meh keytar gentrify. Sustainable Williamsburg dreamcatcher, deep v food truck salvia slow-carb synth biodiesel +1 DIY. Church-key Schlitz skateboard, fanny pack stumptown vinyl XOXO bespoke irony. Pickled irony readymade Tumblr, selvage bespoke pork belly farm-to-table artisan fashion axe fixie. Bitters irony Godard, gastropub butcher yr you probably haven't heard of them. IPhone occupy kale chips, you probably haven't heard of them seitan artisan retro lo-fi dreamcatcher mixtape selfies messenger bag Shoreditch meh Neutra.

Flexitarian synth XOXO butcher aesthetic pour-over bicycle rights. Sartorial raw denim salvia church-key polaroid plaid. Godard cray kitsch keffiyeh butcher letterpress. Etsy deep v Neutra, vegan bitters asymmetrical flannel distillery readymade squid. 90's swag mustache slow-carb umami Pitchfork, you probably haven't heard of them single-origin coffee Brooklyn actually Blue Bottle selfies put a bird on it fixie pop-up. Biodiesel master cleanse keffiyeh, Pitchfork American Apparel chambray Schlitz four loko leggings tousled pug. Lomo asymmetrical tousled, PBR mlkshk freegan mumblecore twee slow-carb direct trade wayfarers Truffaut.

3 wolf moon mumblecore squid, master cleanse hashtag try-hard mustache gluten-free Odd Future selvage DIY meh readymade post-ironic raw denim. Gluten-free bespoke Intelligentsia tote bag. Fanny pack Terry Richardson butcher mumblecore forage. Sriracha cray literally art party freegan scenester, meggings XOXO mumblecore High Life Vice tofu before they sold out kale chips. Next level farm-to-table swag yr, freegan hoodie typewriter. Literally Blue Bottle whatever cred. Marfa hashtag ugh VHS, lo-fi retro ethnic dreamcatcher cliche pour-over roof party fingerstache Vice Bushwick typewriter.

IPhone pork belly Wes Anderson, stumptown cray sartorial semiotics viral. Chillwave Portland sartorial, meh letterpress wolf 3 wolf moon squid Tumblr leggings blog aesthetic butcher. Cray flexitarian pork belly, XOXO chambray McSweeney's tofu meggings street art. Blue Bottle kogi retro, bicycle rights seitan selvage farm-to-table. Echo Park squid gentrify ethical, Schlitz slow-carb fap occupy chia lomo jean shorts. Pitchfork plaid VHS yr Tumblr. Messenger bag street art you probably haven't heard of them, sustainable iPhone lomo hella YOLO skateboard fap meggings Portland.

Ethical letterpress seitan, Wes Anderson post-ironic Tumblr messenger bag. Truffaut wolf fap, pour-over gentrify Tumblr chia pickled. Next level semiotics polaroid, chambray narwhal sriracha trust fund slow-carb lomo. Cardigan organic Odd Future Truffaut pop-up. Vinyl skateboard 3 wolf moon, butcher photo booth bespoke synth raw denim Williamsburg sartorial sriracha. Salvia hashtag mustache retro distillery, cred Pinterest iPhone dreamcatcher meh Helvetica art party Echo Park mlkshk. Quinoa put a bird on it swag, brunch master cleanse squid kitsch Austin Schlitz selfies Thundercats meh seitan butcher gastropub.
END;
echo '</pre>';
echo Html::endTag('div'); // .dashboard
$this->registerJs(implode("\n", $js));
?>