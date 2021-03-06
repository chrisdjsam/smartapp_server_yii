<?php
/* @var $this RobotController */
/* @var $model Robot */
/* @var $form CActiveForm */
$user_role_id = Yii::app()->user->UserRoleId;
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'robot-form',
			'enableAjaxValidation'=>true,
			'clientOptions' => array('validateOnSubmit'=>true),
	)); ?>
	<?php if($user_role_id !== '2'){?>
	<div class="row">
		<?php echo $form->labelEx($robot_type_model,'type'); ?>
		<?php echo $form->dropDownList($robot_type_model,'type', CHtml::listData(RobotTypes::model()->findAll(array('order'=>'type')), 'id', 'concatened'), $selected); ?>
		<?php echo $form->error($robot_type_model,'type'); ?>
	</div>
	<?php }?>
	<?php if($user_role_id == '2'){
		$disabled = 'disabled';
	}else{
		$disabled = '';
	}

	?>
	<div class="row">
		<?php echo $form->labelEx($model,'serial_number'); ?>
		<?php echo $form->textField($model,'serial_number',array('size'=>30,'maxlength'=>100, 'disabled'=>$disabled)); ?>
		<?php echo $form->error($model,'serial_number'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('disabled'=> $model->isNewRecord ? false : true, 'size'=>30,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<?php if($user_role_id !== '2'){?>
	<div class="row">
		<?php echo $form->labelEx($model,'sleep_time'); ?>
		<?php echo $form->textField($model,'sleep_time',array('size'=>30,'maxlength'=>100)); ?>
		<span class="robot_time_instruct">sleep time in seconds.</span>
		<?php echo $form->error($model,'sleep_time'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'lag_time'); ?>
		<?php echo $form->textField($model,'lag_time',array('size'=>30,'maxlength'=>100)); ?>
		<span class="robot_time_instruct">wakeup time in seconds.</span>
		<?php echo $form->error($model,'lag_time'); ?>
	</div>
	<?php }?>
	<div class="row-buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class'=>"neato-button",  "title" => "Add")); ?>
		<?php echo CHtml::button('Cancel', array('class'=>"neato-button cancel_add_robot",  "title" => "Cancel")); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
<!-- form -->
</fieldset>
<script>
    $('input.cancel_add_robot').click(function(){
        window.location = '<?php echo $this->createUrl('robot/list')?>';
    });
</script>
