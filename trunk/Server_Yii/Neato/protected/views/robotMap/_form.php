<?php
/* @var $this RobotMapController */
/* @var $model RobotMap */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'robotMap-form',
	'action' =>$model->isNewRecord ? $this->createUrl('api/RobotMap/add') : $this->createUrl('api/RobotMap/update'),
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
	
	<div class="row">
	<input type="hidden" id="serial_number" name="serial_number" value= "<?php echo $sr_no;?>" />
	<?php if(!$model->isNewRecord){?>
	<input type="hidden" name="map_id" value= "<?php echo $model->id;?>" />
	<input type="hidden"  name="xml_data_version" value= "<?php echo $xml_version;?>" />
	<input type="hidden" name="blob_data_version" value= "<?php echo $blob_version;?>" />
	<?php }?>
	 
	<?php echo $form->labelEx($model,'serial_no'); ?>
	<?php echo $form->textField($model,'serial_no',array('size'=>30,'maxlength'=>100,'disabled'=>'disabled', 'value' => $sr_no)); ?>
	<?php echo $form->error($model,'serial_no'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'xml_data_file_name'); ?>
	<?php echo $form->fileField($model,'xml_data_file_name'); ?>
	<?php echo $form->error($model,'xml_data_file_name'); ?>
	</div>
	
	<div class="row">
	<?php echo $form->labelEx($model,'blob_data_file_name'); ?>
	<?php echo $form->fileField($model,'blob_data_file_name'); ?>
	<?php echo $form->error($model,'blob_data_file_name'); ?>
	</div>

	<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class'=>"neato-button",  "title" => "Add")); ?>
	<a href="<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($id)))?>" title="Cancel" class="neato-button" id="cancel_upload">Cancel</a>
	</div>

	<?php $this->endWidget(); ?>
	
</div>
<!-- form -->
</fieldset>

<script>
	var options = { 
		beforeSubmit: showRequest,
		complete: showComplete    	    
	}; 
 
	// pass options to ajaxForm 
	jQuery('#robotMap-form').ajaxForm(options);

	function showRequest(formData, jqForm, options) {
		showWaitDialog();
	}
	
	function showComplete( response_data, statusText, xhr, $form){
		hideWaitDialog();
		response_data = $.parseJSON(response_data.responseText);
		
		if(response_data.status === 0){
			var redirect_url = $('#cancel_upload').attr('href');
			generate_noty("success", "You have successfully updted a robot map data.");
			window.location = location.protocol+'//'+window.location.hostname+redirect_url;
			
		}else if(response_data.status === -1){
       	 	generate_noty("error", response_data.message);
		}
	}

</script>

