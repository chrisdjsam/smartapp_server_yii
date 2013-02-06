<?php
/* @var $this RobotMapController */
/* @var $model RobotMap */

$this->pageTitle='Update Robot Map- ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
// 		$model->name=>array('view','id'=>$model->id),
		'Update',
);

?>
<fieldset class='data-container static-data-container'>
<p class="list_details">Please update blob data file.</p>

	<?php echo $this->renderPartial('_form', array('model'=>$model, 'sr_no' => $sr_no, 'id'=> $id )); ?>