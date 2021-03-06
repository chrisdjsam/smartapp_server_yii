<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<fieldset class='data-container static-data-container'>
	<legend>Add User</legend>
	<p class="list_details">
		Please enter all the required fields to create a user.
		<br />
		If you select 'Admin' as a user role, this new user would be created with Administrative privileges.
	</p>
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'user-form',
				'enableAjaxValidation'=>true,
				'clientOptions' => array('validateOnSubmit'=>true),
				'focus'=>array($model,'email'),
)); ?>
		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email',array('size'=>30, 'cols'=>128, 'class' => 'user-add-form-input')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>30, 'cols'=>128, 'class' => 'user-add-form-input')); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('size'=>30,'maxlength'=>100, 'class' => 'user-add-form-input')); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'confirm_password'); ?>
			<?php echo $form->passwordField($model,'confirm_password',array('size'=>30,'maxlength'=>100, 'class' => 'user-add-form-input')); ?>
			<?php echo $form->error($model,'confirm_password'); ?>
		</div>
		<div class="row">
			<label>User Role *</label>
			<select name="user_role" class="user-add-select">
				<option value="-1">Select</option>
				<option value="1">Admin</option>
				<option value="2">Customer Support</option>
				<option value="3">User</option>
			</select>
		</div>
		<div class="row-buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class'=>"neato-button_alt associate_user_btn",  "title" => "Add")); ?>
			<a href="<?php echo $this->createUrl('user/list')?>" title="Cancel" class="neato-button_alt">Cancel</a>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- form -->
</fieldset>
