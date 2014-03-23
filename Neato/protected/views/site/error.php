<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle='Error - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Error',
);
?>
<div class="error" style="text-align: center">
	<img alt="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/404-error-page.png">
</div>
