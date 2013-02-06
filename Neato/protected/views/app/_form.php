<?php
/* @var $this AppController */
/* @var $model AppInfo */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'appVersion-form',
	'action' =>$model->isNewRecord ? $this->createUrl('api/App/AddApp') : $this->createUrl('api/App/AppUpdate'),
// 	'enableAjaxValidation'=>true,
// 	'enableClientValidation'=>true,
// 	'clientOptions' => array('validateOnSubmit'=>true),
)); ?>
	
	Fields marked with asterisk (*) are mandatory.
	
	<div class="row">
		<?php echo $form->labelEx($model,'app_id'); ?>
		
		<?php if(!$model->isNewRecord){?>
			<input type="hidden" name="app_id" value= "<?php echo $model->app_id;?>" />
		<?php echo $form->textField($model,'app_id',array('size'=>30,'maxlength'=>20,'disabled'=>'disabled', 'value' => $model->app_id)); ?>
		<?php }else{?>
		<?php echo $form->textField($model,'app_id',array('size'=>30,'maxlength'=>20,)); ?>
		<?php }?>
		
		<?php echo $form->error($model,'app_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'current_app_version'); ?>
		<?php echo $form->textField($model,'current_app_version',array('size'=>30,'maxlength'=>200,)); ?>
		<?php echo $form->error($model,'current_app_version'); ?>
	</div>
	
	<div class="row">	 
	<?php echo $form->labelEx($model,'os_type'); ?>
	<?php $data = array('Android'=>'Android');?>
	<?php echo $form->dropDownList($model, 'os_type', $data);?>
	<?php echo $form->error($model,'os_type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'os_version'); ?>
		<?php echo $form->textField($model,'os_version',array('size'=>30,'maxlength'=>500,)); ?>
		<?php echo $form->error($model,'os_version'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'latest_version'); ?>
		<?php echo $form->textField($model,'latest_version',array('size'=>30,'maxlength'=>500,)); ?>
		<?php echo $form->error($model,'latest_version'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'latest_version_url'); ?>
		<?php echo $form->textField($model,'latest_version_url',array('size'=>30,'maxlength'=>500,)); ?>
		<?php echo $form->error($model,'latest_version_url'); ?>
	</div>

	
	<div class="row">	 
	<?php echo $form->labelEx($model,'upgrade_status'); ?>
	<?php $data = $status_array;?>
	<?php echo $form->dropDownList($model, 'upgrade_status', $data);?>
	<?php echo $form->error($model,'upgrade_status'); ?>
	</div>

	<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class'=>" add-update-app-version neato-button","title" => "Add")); ?>
		<a href="<?php echo $this->createUrl('app/list')?>" title="Cancel" class="neato-button" id="cancel_update">Cancel</a>
	</div>
	<?php $this->endWidget(); ?>
	
</div>
</fieldset>
<script>
	var options = { 
		beforeSubmit: showRequest,
		complete: showComplete    	    
	}; 


	$(document).ready(function(){ 
	// pass options to ajaxForm 
	jQuery('#appVersion-form').ajaxForm(options);
	
	});

	function showRequest(formData, jqForm, options) {
		showWaitDialog();
	}
	
	function showComplete( response_data, statusText, xhr, $form){

		hideWaitDialog();
		response_data = $.parseJSON(response_data.responseText);
		if(response_data.status === 0){
			var redirect_url = $('#cancel_update').attr('href');
			generate_noty("success", response_data.result);
			window.location = location.protocol+'//'+window.location.hostname+redirect_url;
			
		}else if(response_data.status === -1){
       	 	generate_noty("error", response_data.message);
		}
	}

</script>