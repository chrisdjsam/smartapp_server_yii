<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
		'Users'=>array('index'),
		$model->name,
);

?>
<h3>User Details</h3>
<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
				'name',
				'email',
				'chat_id',
				'chat_pwd',
		),
)); ?>
