<?php
/* @var $this UsersRobotController */
/* @var $model UsersRobot */
$this->pageTitle='Add User-Robot Association - ' . Yii::app()->name;
$this->breadcrumbs=array(
	'Users Robots'=>array('index'),
	'Create',
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>