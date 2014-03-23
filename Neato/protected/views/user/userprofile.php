<?php
/* @var $this UserController */
/* @var $model User */

if (Yii::app()->user->id !== $model->id) {
	$this->pageTitle = 'Profile Details - ' . Yii::app()->name;
} else {
	$this->pageTitle = 'My Profile - ' . Yii::app()->name;
}

$this->breadcrumbs = array(
		'Users' => array('index'),
		$model->name,
);

$modelcountrycode = new CountryCodeList();
$userRole = Yii::app()->user->UserRoleId;
?>
<fieldset class='data-container static-data-container'>
	<?php
	$legend_message = "My Profile";
	if($userRole !== '2'){
		if (Yii::app()->user->id !== $model->id) {
			$legend_message = "Profile details for $model->name";
		}
	}else{
		$legend_message = "Profile details";
	}
	$is_wp_enabled = Yii::app()->params['is_wp_enabled'];
	?>
	<legend>
		<?php echo $legend_message; ?>
	</legend>
	<?php if (Yii::app()->user->id == $model->id) { ?>
	<div class="view-user-profile">
		<p class="list_details">
			Please review your profile information.
			<br />
			<?php if(!$is_wp_enabled){ ?>
			Click on edit to update profile.
			<br />
			<?php } ?>
			<?php
			if(Yii::app()->user->isAdmin && !$is_wp_enabled){

				if($userRole != '2'){
					?>
			Now we require that you validate your registered email within 1 hour of registration.
			<br />
			For some reason if user could not validate your email, as an admin, you can validate it by selecting "Yes" against the "Is
			email validated?".
			<br />
			<?php
				}
			}
			?>
		</p>
	</div>
	<div class="update-user-profile">
		<p class="list_details">
			<?php if(!$is_wp_enabled){ ?>
			Click on save button to update profile.
			<br />
			<?php } ?>
		</p>
	</div>
	<?php } ?>
	<?php
	$html_string = '';
	if ($model->doesRobotAssociationExist()) {
		$is_first_robot = true;
		$html_string = '';
		foreach ($model->usersRobots as $value) {
			if (!$is_first_robot) {
				$html_string .= ",&nbsp";
			}
			$is_first_robot = false;
			$html_string .= "<a class='qtiplink robot-qtip' title='View details of (" . $value->idRobot->serial_number . ")' rel='" . $this->createUrl('robot/popupview', array('h' => AppHelper::two_way_string_encrypt($value->idRobot->id))) . "' href='" . $this->createUrl('robot/view', array('h' => AppHelper::two_way_string_encrypt($value->idRobot->id))) . "'>" . $value->idRobot->serial_number . "</a>";
		}
	}
	?>
	<?php  if(!$is_wp_enabled){
		if (Yii::app()->user->isAdmin && Yii::app()->user->id !== $model->id) {
			?>
	<div class="edit-user-profile">
		<p class="list_details">
			You can delete this user by clicking on delete user button.
			<br />
			You can reset password for this user by clicking on reset password button.
			<br />
			Please note that deleting a user would also delete the user-robot associations for this specific user.
			<br />
			Please note that resetting password for this user would reset the user's current password and send an email mentioning user's
			new password.
			<br />
			Click on edit to update user profile.
			<br />
			Now we require that user validates his registered email within 1 hour of registration.
			<br />
			For some reason if user could not validate his email, as
			<?php if($userRole != '2'){?>
			an admin
			<?php }else{?>
			a support
			<?php }?>
			, you can validate his email by selecting "Yes" against the "Is email validated?".
			<br />
		</p>
	</div>
	<div class="update-user-profile">
		<p class="list_details">
			You can delete this user by clicking on delete user button.
			<br />
			You can reset password for this user by clicking on reset password button.
			<br />
			Please note that deleting a user would also delete the user-robot associations for this specific user.
			<br />
			Please note that resetting password for this user would reset the user's current password and send an email mentioning user's
			new password.
			<br />
			Click on save button to update profile.
			<br />
			Now we require that user validates his registered email within 1 hour of registration.
			<br />
			For some reason if user could not validate his email, as
			<?php if($userRole != '2'){?>
			an admin
			<?php }else{?>
			a support
			<?php }?>
			, you can validate his email by selecting "Yes" against the "Is email validated?".
			<br />
		</p>
	</div>
	<div class="action_delete_reset">
		<div class="action-button-container">
			<a href="<?php echo $this->createUrl('user/Delete', array('h' => AppHelper::two_way_string_encrypt($model->id))); ?>"
				class="user-neato-button neato-button requires-confirmation-delete" title="Delete User">Delete User</a>
			<a href="<?php echo $this->createUrl('user/Resetpassword', array('h' => AppHelper::two_way_string_encrypt($model->id))); ?>"
				class="user-neato-button neato-button requires-confirmation-reset-password" title="Reset Password">Reset Password</a>
		</div>
	</div>
	<?php }
            }?>
	<?php

	$chat_attribute = array(
			'label' => 'Associated Robots',
			'type' => 'raw',
			'value' => $html_string,
	);

	$cDetailAttribute = array(
			'email',
			'alternate_email',
			$chat_attribute,
			'country_code',
			'opt_in',
	);

	if(Yii::app()->user->isAdmin){
		$cDetailAttribute = array(
				'email',
				'alternate_email',
				$chat_attribute,
				'chat_id',
				'chat_pwd',
				'created_on',
				'country_code',
				'opt_in',
		);
	}
	if($userRole == '2'){
		$cDetailAttribute = array(
				'email',
				'alternate_email',
				$chat_attribute,
				'created_on',
				'country_code',
				'opt_in',
		);
	}
	$this->widget('zii.widgets.CDetailView', array(
			'data' => $model,
			'attributes' => $cDetailAttribute,
			'id' => 'user_profile_detail'
	));
	?>
	<?php if(!$is_wp_enabled){ ?>
	<div id="edit_user_profile_btn" class='neato-button_alt right' title="Edit">Edit</div>
	<?php }?>
	<div class="form update_user_form hide">
		<?php
		$form = $this->beginWidget('CActiveForm', array(
				'id' => 'update_user_data-form',
				'focus' => array($update_user, 'alternate_email'),
				'enableClientValidation' => true,
				'clientOptions' => array('validateOnSubmit' => true),
		));
		?>
		<?php if($userRole == '2'){
			$disabled = 'disabled';
		}else{
			$disabled = '';
		}

		?>
		<div class="row">
			<?php echo $form->labelEx($update_user, 'name', array('class' => 'update_user_lable')); ?>
			<?php echo $form->textField($update_user, 'name', array('size' => 30, 'cols' => 128, 'tabindex' => 2, 'class' => 'update_user_input', 'disabled' => $disabled)); ?>
			<?php echo $form->error($update_user, 'name', array('class' => 'prepend-4 errorMessage')); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($update_user, 'alternate_email', array('class' => 'update_user_lable')); ?>
			<?php echo $form->textField($update_user, 'alternate_email', array('size' => 30, 'cols' => 128, 'tabindex' => 1, 'class' => 'update_user_input')); ?>
			<?php echo $form->error($update_user, 'alternate_email', array('class' => 'prepend-4 errorMessage')); ?>
		</div>
		<div class="row dropdownlist">
			<?php echo $form->labelEx($update_user, 'country', array('class' => 'update_user_lable')); ?>
			<?php echo $form->dropDownList($modelcountrycode,'iso2', CHtml::listData(CountryCodeList::model()->findAll(array('order'=>'short_name')), 'iso2', 'short_name'), array('options'=>array($update_user->country_code => array('selected'=>'selected')))); ?>
			<?php echo $form->error($update_user, 'country', array('class' => 'prepend-4 errorMessage')); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model, 'Promotional Newsletter?', array('class' => 'update_user_lable')); ?>
			<?php echo $form->checkbox($update_user, 'opt_in', array('class' => 'checkbox-style')); ?>
		</div>
		<?php if(Yii::app()->user->isAdmin) { ?>
		<div class="row" style="height: 40px;">
			<?php echo $form->labelEx($update_user, 'is_validated', array('class' => 'update_user_lable', 'style' => 'margin-top: 2px;')); ?>
			<input type="radio" name="is_validated" class="left" <?php if($update_user->is_validated == 1){ ?> checked="checked" <?php } ?>
				value="1">
			<label class="update_user_lable update_user_radio">Yes</label>
			<input type="radio" name="is_validated" class="left" <?php if($update_user->is_validated == 0){ ?> checked="checked" <?php } ?>
				value="0">
			<label class="update_user_lable update_user_radio">No</label>
			<?php echo $form->error($update_user, 'is_validated', array('class' => 'prepend-4 errorMessage')); ?>
			<br />
		</div>
		<?php }?>
		<input id="update_user_data_flag" type="hidden" name="update_user_data_flag" value="N">
		<div class="row-buttons prepend-4">
			<?php echo CHtml::button('Save', array('id' => 'update_user_data', 'class' => "neato-button", "title" => "Update Profile")); ?>
			<?php echo CHtml::button('Cancel', array('id' => 'update_user_data_cancel', 'class' => "neato-button", "title" => "Cancel")); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- form -->
</fieldset>
<script>
    $('.requires-confirmation-delete').click(function(){
        if(confirm("Are you sure you want to delete this user?")){
            return true;
        }else{
            return false;
        }
    });

    $('.requires-confirmation-reset-password').click(function(){
        if(confirm("Are you sure you want to reset pasword for this user?")){
            return true;
        }else{
            return false;
        }
    });

    $(document).ready(function(){
    	hideUpdateAndShowEditInfo();
    	$('.update-user-profile').hide();
        $('#update_user_data').click(function(){

            $('#update_user_data_flag').val('Y');

            $('#update_user_data-form').submit();

        });

        <?php if(!empty($update_user->errors)) {   ?>
            hideUserProfile();
        <?php }?>

        $('#edit_user_profile_btn').click(function(){
            hideUserProfile();
        });

        $('#update_user_data_cancel').click(function(){
        	hideUpdateAndShowEditInfo();
            $('#user_profile_detail').show();
            $('.update_user_form').hide();
            $('#edit_user_profile_btn').show();
            $('.view-user-profile').show();
        });
    });

	function hideUpdateAndShowEditInfo(){
		$('.edit-user-profile').show();
		$('.update-user-profile').hide();
	}

	function hideUserProfile () {
	    $('.update_user_form').show();
	    $('#user_profile_detail').hide();
	    $('#edit_user_profile_btn').hide();
	    $('.view-user-profile').hide();
	    $('.update-user-profile').show();
	    $('.edit-user-profile').hide();

}
</script>