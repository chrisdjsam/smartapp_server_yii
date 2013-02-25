<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle='Account Recovery - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Users'=>array('index'),
		'Register',
);


?>
<fieldset class='data-container static-data-container'>
	<legend>Account Recovery</legend>
	<p class="list_details">
		Please enter your registered email and you would receive an email
		mentioning your temporary password.<br /> We recommend that you change
		this temporary password after login.
	</p>

	<div class="form">

		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'forgotpassword-form',
				'focus'=>array($model,'email'),
				'enableAjaxValidation'=>true,
				//'enableClientValidation'=>true,
				'clientOptions' => array('validateOnSubmit'=>true),
)); ?>


		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email',array('size'=>30, 'cols'=>128)); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row-buttons">
			<?php echo CHtml::submitButton('Request', array('class'=>"neato-button",  "title" => "Request")); ?>
			<a href="<?php echo $this->createUrl('user/login')?>" title="Cancel"
				class="neato-button">Cancel</a>
		</div>

		<?php $this->endWidget(); ?>

	</div>
	<!-- form -->
</fieldset>
