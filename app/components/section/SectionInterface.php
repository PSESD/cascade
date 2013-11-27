<?php
namespace app\components\section;

interface SectionInterface extends \infinite\web\RenderInterface {
	public static function generateSectionId($name);
	public function setTitle($title);
	public function getSectionTitle();
	public function defaultItems($parent = null);
	public function getTitle();
}
?>