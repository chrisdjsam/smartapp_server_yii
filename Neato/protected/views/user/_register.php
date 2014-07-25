<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

$this->pageTitle='Register - '.Yii::app()->name;
$this->breadcrumbs=array(
		'Register',
);
?>
<label class='login-heading'>Register</label>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'register-form',
			'enableAjaxValidation'=>true,
			'clientOptions' => array('validateOnSubmit'=>true),
)); ?>
	<?php
	$modelcountrycodelist = new CountryCodeList();
	?>
	<div class="section register_section">
		<div class="section_left">
			<div class="row">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>30, 'cols'=>128,'tabindex'=>1)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name',array('size'=>30, 'cols'=>128,'tabindex'=>2)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
			<div class="row-buttons register-button">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Register' : 'Save', array('class'=>"neato-button",  "title" => "Register",'tabindex'=>5)); ?>
			</div>
		</div>
		<div class="section_right">
			<div class="row">
				<?php echo $form->labelEx($modelcountrycodelist,'country'); ?>
				<?php echo $form->dropDownList($modelcountrycodelist,'iso2', CHtml::listData(CountryCodeList::model()->findAll(array('order'=>'short_name')), 'iso2', 'short_name'),array('options'=>array('US'=>array('selected'=>'selected')), 'class'=>'full-width')); ?>
				<?php echo $form->error($modelcountrycodelist,'iso2'); ?>
			</div>
			<div class="row">
				<input type="hidden" value="0" name="User[opt_in]">
				<label>Do you want to receive promotional newsletter?</label>
				<input id="UserAddForm_is_admin" type="checkbox" value="1" name="User[opt_in]" yes="1">
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password',array('size'=>30,'maxlength'=>100,'tabindex'=>3)); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'confirm_password'); ?>
				<?php echo $form->passwordField($model,'confirm_password',array('size'=>30,'maxlength'=>100,'tabindex'=>4)); ?>
				<?php echo $form->error($model,'confirm_password'); ?>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<!-- form -->
