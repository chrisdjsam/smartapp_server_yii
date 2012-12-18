<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Add Robot - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'Create',
);

?>

<fieldset class='data-container static-data-container'>
	<legend>Add Robot</legend>
	<p class="list_details">
		Please enter serial number to add a robot.<br /> You can not enter
		same serial number twice.
	</p>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>