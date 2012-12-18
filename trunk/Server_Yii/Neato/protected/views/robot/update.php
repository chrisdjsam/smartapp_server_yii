<?php
/* @var $this RobotController */
/* @var $model Robot */

$this->pageTitle='Update Robot - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		$model->name=>array('view','id'=>$model->id),
		'Update',
);

?>
<fieldset class='data-container static-data-container'>
	<legend>Update Robot</legend>
	<p class="list_details">Please update the robot information.</p>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>