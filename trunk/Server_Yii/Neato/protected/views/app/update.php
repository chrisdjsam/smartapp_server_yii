<?php
$this->pageTitle='Edit App Version- ' . Yii::app()->name;
// $this->breadcrumbs=array(
// 		'Robots'=>array('index'),
// 		'Create',
// );

?>

<fieldset class='data-container static-data-container'>
	
	<legend>Edit Version Control</legend>
	<p class="list_details">Please fill application version details.</p>

	<?php echo $this->renderPartial('_form', array('model'=>$model, 'status_array' => $status_array)); ?>

	