<?php
/* @var $this RobotMapController */
/* @var $model RobotMap */
$this->pageTitle='Add Robot Map- ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'Create',
);

?>

<fieldset class='data-container static-data-container'>
	
	<p class="list_details">Please add xml and blob data files. Atleast one file is required.</p>

	<?php echo $this->renderPartial('_form', array('model'=>$model, 'sr_no' => $sr_no, 'id'=> $id )); ?>

	