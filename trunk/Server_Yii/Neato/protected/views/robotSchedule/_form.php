<?php
/* @var $this RobotScheduleController */
/* @var $model RobotSchedule */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'robotSchedule-form',
	'action' =>$model->isNewRecord ? $this->createUrl('api/RobotSchedule/add') : $this->createUrl('api/RobotSchedule/update'),
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
	
	<input type="hidden" id="serial_number" name="serial_number" value= "<?php echo $sr_no;?>" />
	<?php if(!$model->isNewRecord){?>
		<input type="hidden" name="robot_schedule_id" value= "<?php echo $model->id;?>" />
		<input type="hidden" name="xml_data_version" value= "<?php echo $xml_version;?>" />
		<input type="hidden" name="blob_data_version" value= "<?php echo $blob_version;?>" />
	<?php }?>
	<div class="row">	 
	<?php echo $form->labelEx($model,'serial_no'); ?>
	<?php echo $form->textField($model,'serial_no',array('size'=>30,'maxlength'=>100,'disabled'=>'disabled', 'value' => $sr_no)); ?>
	<?php echo $form->error($model,'serial_no'); ?>
	</div>
	
	<div class="row">	 
	<?php echo $form->labelEx($model,'type'); ?>
	<?php $data = array('Basic'=>'Basic','Advanced'=>'Advanced');?>
	<?php echo $form->dropDownList($model, 'type', $data);?>
	<?php echo $form->error($model,'type'); ?>
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
	<div class="row-buttons">
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
	jQuery('#robotSchedule-form').ajaxForm(options);

	function showRequest(formData, jqForm, options) {
		showWaitDialog();
	}
	
	function showComplete( response_data, statusText, xhr, $form){
		
		response_data = $.parseJSON(response_data.responseText);
		
		if(response_data.status === 0){
                    updateScheduleData();       
		}else if(response_data.status === -1){
       	 	generate_noty("error", response_data.message);
		}
	}
        
        function updateScheduleData(){
                  
            var robot_id = '<?php echo $sr_no;?>';
            
            $.ajax({
                type: 'POST',
                url: app_base_url +'/api/RobotSchedule/setKeyValueAndSendXMPP',
                dataType: 'json',
                data: {
                    serial_number: robot_id
                },
                success: function(r) {
                    
                    hideWaitDialog();
                    
                    if(r.code == 0){
                        var redirect_url = $('#cancel_upload').attr('href');
                        generate_noty("success", "You have successfully updted a robot schedule data.");
                        window.location = location.protocol+'//'+window.location.hostname+redirect_url;
                    } else {
                        generate_noty("error", "Error on while adding/updating schedule");
                    }
                },
                error: function(r) {
                    generate_noty("error", "Error on while setting schedule key, value and sending XMPP message");
                }

        
            });
                  
        }
        

</script>

