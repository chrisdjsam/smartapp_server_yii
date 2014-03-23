<?php
/* @var $this RobotController */
/* @var $form CActiveForm */

$focus = $robot_type_model->isNewRecord ? array($robot_type_model,'type') : array($robot_type_model,'name');
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'robot-type-form',
			'focus'=>$focus,
			'enableAjaxValidation'=>true,
			'clientOptions' => array('validateOnSubmit'=>true),
	)); ?>
	<div class="row">
		<?php
		if($robot_type_model->isNewRecord){
			echo $form->labelEx($robot_type_model,'type');
			echo $form->textField($robot_type_model,'type',array('size'=>30,'maxlength'=>100));
		}else {
			?>
		<?php echo $form->labelEx($robot_type_model,'type', array('class'=>'left robot_type_label')); ?>
		<label class="required" for="RobotTypeMetadataForm_type">
			<?php echo $robot_type_model->type; ?>
		</label>
		<input type="hidden" name="type" value="<?php echo $robot_type_model->type; ?>">
		<?php

		}
		?>
		<?php echo $form->error($robot_type_model,'type'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($robot_type_model,'name'); ?>
		<?php echo $form->textField($robot_type_model,'name',array('size'=>30,'maxlength'=>100)); ?>
		<?php echo $form->error($robot_type_model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($robot_type_model,'sleep_time'); ?>
		<?php echo $form->textField($robot_type_model,'sleep_time',array('size'=>30,'maxlength'=>100)); ?>
		<span class="robot_time_instruct">Sleep time is in minutes.</span>
		<?php echo $form->error($robot_type_model,'sleep_time'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($robot_type_model,'lag_time'); ?>
		<?php echo $form->textField($robot_type_model,'lag_time',array('size'=>30,'maxlength'=>100)); ?>
		<span class="robot_time_instruct">Lag time is in seconds.</span>
		<?php echo $form->error($robot_type_model,'lag_time'); ?>
	</div>
	<div class="row-buttons">
		<?php echo CHtml::submitButton($robot_type_model->isNewRecord ? 'Add' : 'Save', array('class'=>"neato-button",  "title" => "Add")); ?>
		<?php echo CHtml::button('Cancel', array('class'=>"neato-button cancel_add_robot_type",  "title" => "Cancel")); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
<!-- form -->
</fieldset>
<script>
    $('input.cancel_add_robot_type').click(function(){
        window.location = '<?php echo $this->createUrl('robot/types')?>';
    });
</script>
