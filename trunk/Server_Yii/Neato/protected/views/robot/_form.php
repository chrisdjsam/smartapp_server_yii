<?php
/* @var $this RobotController */
/* @var $model Robot */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'robot-form',
		'focus'=>array($model,'serial_number'),
		'enableAjaxValidation'=>true,
		//'enableClientValidation'=>true,
		'clientOptions' => array('validateOnSubmit'=>true),
	)); ?>

	<div class="row">
	<?php echo $form->labelEx($model,'serial_number'); ?>
	<?php echo $form->textField($model,'serial_number',array('size'=>30,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'serial_number'); ?>
	</div>
	
	<div class="row">
	<?php echo $form->labelEx($model,'name'); ?>
	<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class'=>"neato-button",  "title" => "Add")); ?>
	<a href="<?php echo $this->createUrl('robot/list')?>" title="Cancel" class="neato-button" >Cancel</a>
	</div>

	<?php $this->endWidget(); ?>

</div>
<!-- form -->
</fieldset>