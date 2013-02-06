<?php
/* @var $this RobotMapController */
/* @var $model RobotMap */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'robotAtlas-form',
	'action' =>$model->isNewRecord ? $this->createUrl('api/RobotAtlas/add') : $this->createUrl('api/RobotAtlas/update'),
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
    	
	<div class="row">
	<input type="hidden" id="serial_number" name="serial_number" value= "<?php echo $sr_no;?>" />
	<?php if(!$model->isNewRecord){?>
	<input type="hidden" name="atlas_id" value= "<?php echo $model->id;?>" />
	<input type="hidden"  name="xml_data_version" value= "<?php echo $xml_version;?>" />
	<?php }?>
	 
	<?php echo $form->labelEx($model,'serial_no'); ?>
	<?php echo $form->textField($model,'serial_no',array('size'=>30,'maxlength'=>100,'disabled'=>'disabled', 'value' => $sr_no)); ?>
	<?php echo $form->error($model,'serial_no'); ?>
	</div>

	<?php if(!$model->isNewRecord){?>
	<div class="row">
	<?php echo $form->labelEx($model,'Delete All Grids'); ?>
		<select style =  "width: 83px"  name = "delete_grids">
		  <option value="0">No</option>
		  <option value="1">Yes</option>
		</select>
	</div>
	
	<div class = "row">
		<?php echo $form->labelEx($model,'XML File Version'); ?>
		<?php echo $form->textField($model,'version',array('size'=>4,'maxlength'=>100,'disabled'=>'disabled',)); ?>
		<?php echo $form->error($model,'XML File Version'); ?>
	</div>
	<?php }?>
	
	<div class="row">
	<?php echo $form->labelEx($model,'xml_data_file_name'); ?>
	<?php echo $form->fileField($model,'xml_data_file_name'); ?>
	<?php echo $form->error($model,'xml_data_file_name'); ?>
	</div>
	
	<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class'=>"neato-button",  "title" => "Add")); ?>

	<a href="<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($id)))?>" title="Cancel" class="neato-button" id="cancel_upload">Cancel</a>
	
		<?php if(!$model->isNewRecord){?>
	<a class="delete-robot-atlas neato-button" href=<?php echo $this->createUrl('api/RobotAtlas/delete',array('atlas_id'=> $model->id))?>
			title="Delete atlas <?php echo $model->id?>">Delete</a>
		<?php }?>
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
	jQuery('#robotAtlas-form').ajaxForm(options);

	function showRequest(formData, jqForm, options) {
		showWaitDialog();
	}
	
	function showComplete( response_data, statusText, xhr, $form){
		hideWaitDialog();
		response_data = $.parseJSON(response_data.responseText);
		
		if(response_data.status === 0){
			var redirect_url = $('#cancel_upload').attr('href');
			generate_noty("success", response_data.result.message);
			window.location = location.protocol+'//'+window.location.hostname+redirect_url;
			
		}else if(response_data.status === -1){
       	 	generate_noty("error", response_data.message);
		}
	}
</script>

