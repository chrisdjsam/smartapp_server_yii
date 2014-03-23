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
	<p class="list_details">Please add grid-id and blob data file.</p>
	<?php echo $this->renderPartial('_form', array('model'=>$model, 'sr_no' => $sr_no, 'id'=> $id )); ?>