<?php
$this->pageTitle='Send Message - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'Create',
);

?>
<fieldset
	class='data-container static-data-container'>
	<form action="api/robot/sendMessage">
		<p class="list_details">Enter your message in the Text area.</p>
		<?php ?>
		<input type="hidden" name="sr_no" value="<?php echo $model->serial_number?>" />
		<input type="hidden" name="id_robot" value="<?php echo $model->id?>" />
		<input type="hidden" name="message" value="<?php echo 'message to robot'?>" />
		<input type="Submit" name='send' value='Send message' class='neato-button neato-button-large' title="Send message to robot" />
	</form>
</fieldset>
