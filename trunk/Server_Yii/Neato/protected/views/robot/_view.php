<?php
/* @var $this RobotController */
/* @var $data Robot */
?>
<div class="view">
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('id')); ?>
		:
	</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('name')); ?>
		:
	</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('serial_number')); ?>
		:
	</b>
	<?php echo CHtml::encode($data->serial_number); ?>
	<br />
</div>
