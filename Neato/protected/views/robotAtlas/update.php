<?php
$this->pageTitle='Update Robot Map- ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'Update',
);

?>
<fieldset class='data-container static-data-container'>
	<p class="list_details">Please update robot atlas.</p>
	<?php echo $this->renderPartial('_form', array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model, 'xml_version'=>$xml_version)); ?>