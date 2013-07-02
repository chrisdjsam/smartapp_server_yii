<?php
/* @var $this RobotController */
/* @var $model Robot */

$this->pageTitle='Update Robot Type - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'RobotTypes'=>array('index'),
		 $robot_type_model->name=>array('view','id'=>$robot_type_model->type),
		'Update Type',
);

?>
<fieldset class='data-container static-data-container'>
	<legend>Update Robot Type</legend>
	<p class="list_details">Please update robot type information.</p>
        
	<?php 
            echo $this->renderPartial('_form_type', array('robot_type_model'=>$robot_type_model)); 
        ?>