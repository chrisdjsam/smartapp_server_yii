<?php
/* @var $this UsersRobotController */
/* @var $model UsersRobot */

$this->breadcrumbs=array(
		'Users Robots'=>array('index'),
		$model->id,
);

$this->menu=array(
		array('label'=>'List UsersRobot', 'url'=>array('index')),
		array('label'=>'Create UsersRobot', 'url'=>array('create')),
		array('label'=>'Update UsersRobot', 'url'=>array('update', 'id'=>$model->id)),
		array('label'=>'Delete UsersRobot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Manage UsersRobot', 'url'=>array('admin')),
);
?>
<h1>
	View UsersRobot #
	<?php echo $model->id; ?>
</h1>
<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
				'id',
				'id_user',
				'id_robot',
		),
)); ?>
