<?php
/* @var $this UsersRobotController */
/* @var $model UsersRobot */
/* @var $form CActiveForm */
?>


<fieldset class='data-container static-data-container'>
	<legend>Add User-Robot Association</legend>

	<p class="list_details">Please select a user email and a robot serial number to associate
		the selected user with the selected robot.</p>
	<div class="form">

		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'users-robot-form',
				'enableAjaxValidation'=>true,
				//'enableClientValidation'=>true,
				'clientOptions' => array('validateOnSubmit'=>true),
		)); ?>


		<?php
		$modeluser=new User();
		$modelrobot=new Robot();
		?>

		<div class="row">
			<?php echo $form->labelEx($modeluser,'user'); ?>
			<?php echo $form->dropDownList($model,'id_user', CHtml::listData(User::model()->findAll(array('order'=>'email')), 'id', 'email'), array('empty'=>'---Select Email---')); ?>
			<?php echo $form->error($model,'id_user'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($modelrobot,'robot_serial_number'); ?>
			<?php echo $form->dropDownList($model,'id_robot', CHtml::listData(Robot::model()->findAll(array('order'=>'serial_number')), 'id', 'serial_number'), array('empty'=>'---Select Serial Number---')); ?>
			<?php echo $form->error($model,'id_robot'); ?>
		</div>

		<div class="row-buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Associate' : 'Save', array('class'=>"neato-button_alt associate_user_btn",  "title" => "Associate")); ?>
			<a href="<?php echo $this->createUrl('usersRobot/list')?>" title="Cancel" class="neato-button_alt">Cancel</a>
		</div>

		<?php $this->endWidget(); ?>

	</div>
	<!-- form -->
</fieldset>
