<?php
/* @var $this UserController */
/* @var $data User */
?>
<div class="view">
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('id')); ?>
		:
	</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>
		:
	</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('Email')); ?>
		:
	</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('Is_admin')); ?>
		:
	</b>
	<?php echo CHtml::encode($data->is_admin); ?>
	<br />
</div>
