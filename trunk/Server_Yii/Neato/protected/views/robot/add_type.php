<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Add Robot Type - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'RobotTypes'=>array('index'),
		'Add Type',
);

?>
<fieldset class='data-container static-data-container'>
	<legend>Add Robot Type</legend>
	<p class="list_details">You can not enter same Robot Type twice.</p>
	<?php 
	echo $this->renderPartial('_form_type', array('robot_type_model'=>$robot_type_model));
	?>