<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle='Change Password - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Users'=>array('index'),
		'Register',
);


?>
<fieldset class='data-container static-data-container'>
	<legend>Change Password</legend>

	<p class="list_details">
		Please enter your old and new passwords.<br /> If old password matches
		your existing password, your password would be updated with the new
		password.
	</p>

	<div class="form">

		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'changepassword-form',
				'focus'=>array($model,'password'),
				'enableAjaxValidation'=>true,
				//'enableClientValidation'=>true,
				'clientOptions' => array('validateOnSubmit'=>true),
)); ?>


		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('size'=>30, 'cols'=>128)); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'newpassword'); ?>
			<?php echo $form->passwordField($model,'newpassword',array('size'=>30, 'cols'=>128)); ?>
			<?php echo $form->error($model,'newpassword'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'confirm_password'); ?>
			<?php echo $form->passwordField($model,'confirm_password',array('size'=>30,'maxlength'=>100)); ?>
			<?php echo $form->error($model,'confirm_password'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Save', array('class'=>"neato-button",  "title" => "Change Password")); ?>
			<a href="<?php echo $this->createUrl('user/userprofile')?>"
				title="Cancel" class="neato-button">Cancel</a>
		</div>

		<?php $this->endWidget(); ?>

	</div>
	<!-- form -->
</fieldset>
