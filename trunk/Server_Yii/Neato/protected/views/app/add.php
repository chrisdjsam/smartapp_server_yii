<?php
$this->pageTitle='Add App Version- ' . Yii::app()->name;
?>
<fieldset class='data-container static-data-container'>
	<legend>Add Version Control</legend>
	<p class="list_details">Please fill application version details.</p>
	<?php echo $this->renderPartial('_form', array('model'=>$model, 'status_array' => $status_array)); ?>