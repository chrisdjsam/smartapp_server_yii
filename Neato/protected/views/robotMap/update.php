<?php
/* @var $this RobotMapController */
/* @var $model RobotMap */

$this->pageTitle='Update Robot Map- ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'Update',
);

?>
<fieldset class='data-container static-data-container'>
	<p class="list_details">Please add or update robot map files. Atleast one file is required.</p>
	<?php echo $this->renderPartial('_form', array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model, 'xml_version'=>$xml_version, 'blob_version'=> $blob_version)); ?>