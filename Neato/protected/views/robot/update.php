<?php
/* @var $this RobotController */
/* @var $model Robot */

$this->pageTitle='Update Robot - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		$model->name=>array('view','id'=>$model->id),
		'Update',
);
$user_role_id = Yii::app()->user->UserRoleId;
?>
<fieldset class='data-container static-data-container'>
	<?php if($user_role_id !== '2'){?>
		<legend>Update  <?php print $model->serial_number; ?></legend>
	<?php }else{?>
		<legend> update <?php print $model->serial_number; ?></legend>
	<?php }?>
	<p class="list_details">
            Click on save button to update robot information.<br />
            <?php if($user_role_id !== '2'){?>
            	If you enter sleep time, you must enter wakeup time and vice versa.
            <?php }?>
        </p>
        
	<?php 
        
            $selected = array('options' => array($model->robotRobotTypes->robot_type_id=>array('selected'=>true)));
            echo $this->renderPartial('_form', array('model'=>$model, 'robot_type_model'=>$robot_type_model, 'selected'=>$selected)); 
        ?>