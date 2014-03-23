<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle='Add User - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Users'=>array('index'),
		'Create',
);
?>
<?php echo $this->renderPartial('_add', array('model'=>$model)); ?>