<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Is_admin')); ?>:</b>
	<?php echo CHtml::encode($data->is_admin); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('chat_id')); ?>:</b>
	<?php echo CHtml::encode($data->chat_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('chat_pwd')); ?>:</b>
	<?php echo CHtml::encode($data->chat_pwd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('language')); ?>:</b>
	<?php echo CHtml::encode($data->language); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_login')); ?>:</b>
	<?php echo CHtml::encode($data->last_login); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prev_last_login')); ?>:</b>
	<?php echo CHtml::encode($data->prev_last_login); ?>
	<br />

	*/ ?>

</div>