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
		Please enter serial number to add a robot.<br /> 
                You can not enter same serial number twice.<br /> 
                If you enter sleep time, you must enter wakeup time and vice versa.
	</p>
        
	<?php 
            $selected = array('empty'=>'---Select Robot Type---');
            echo $this->renderPartial('_form', array('model'=>$model, 'robot_type_model'=>$robot_type_model, 'selected'=>$selected)); 
        ?>