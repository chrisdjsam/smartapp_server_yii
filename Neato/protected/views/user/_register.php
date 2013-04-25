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
			//'enableClientValidation'=>true,
			'clientOptions' => array('validateOnSubmit'=>true),
			'focus'=>array($model,'email'),
)); ?>

	<div class="section register_section">
		<div class="section_left">

			<div class="row">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>30, 'cols'=>128,'tabindex'=>1)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
                    
<!--                        <div class="row">
                            <?php // echo $form->labelEx($model, 'alternate_email'); ?>
                            <?php // echo $form->textField($model, 'alternate_email', array('size' => 30, 'cols' => 128, 'tabindex' => 1)); ?>
                            <?php // echo $form->error($model, 'alternate_email'); ?>
                        </div>-->

			<div class="row">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name',array('size'=>30, 'cols'=>128,'tabindex'=>2)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>

			<div class="row-buttons">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Register' : 'Save', array('class'=>"neato-button",  "title" => "Register",'tabindex'=>5)); ?>
			</div>
			<div class="social_login_connect_with">
				<b>Or connect with </b>
			</div>
			<img alt="Facebook Login"
				src="<?php echo Yii::app()->request->baseUrl."/images/facebook.png"?>"
				class='btn-facebook look-like-a-link' title="Facebook">

		</div>

		<div class="section_right">
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
