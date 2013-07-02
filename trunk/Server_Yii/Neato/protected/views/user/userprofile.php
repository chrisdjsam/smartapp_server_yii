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
?>

<fieldset class='data-container static-data-container'>

    <?php
    $legend_message = "My Profile";
    if (Yii::app()->user->id !== $model->id) {
        $legend_message = "Profile details for $model->name";
    }
    ?>
    <legend>
        <?php echo $legend_message; ?>
    </legend>
    <?php if (Yii::app()->user->id == $model->id) { ?>
        <p class="list_details">
            Please review your profile information.<br />
            Click on edit to update profile.<br />
            <?php 
            if(Yii::app()->user->isAdmin){
                ?>
                Now we require that you validate your registered email within 1 hour of registration. <br/>
                For some reason if you could not validate your email, as an admin, you can validate it by selecting "yes" against the "Is email validated?".<br />
                    <?php
            }
            ?>
            
        </p>
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

    <?php if (Yii::app()->user->isAdmin && Yii::app()->user->id !== $model->id) { ?>

        <p class="list_details">
            You can delete this user by clicking on delete user button.<br /> You
            can reset password for this user by clicking on reset password button.<br />
            Please note that deleting a user would also delete the user-robot
            associations for this specific user.<br /> Please note that resetting
            password for this user would reset the user's old password and send an
            email mentioning user's new password.<br />And user would not able to
            login using old password.<br />
            Click on edit to update user profile.<br />
            Now we require that user validates his registered email within 1 hour of registration. <br/>
            For some reason if user could not validate his email, as an admin, you can validate his email by selecting "yes" against the "Is email validated?".<br />
        </p>
        <div class="action_delete_reset">
            <div class="action-button-container">
                <a href="<?php echo $this->createUrl('user/Delete', array('h' => AppHelper::two_way_string_encrypt($model->id))); ?>" class="user-neato-button neato-button requires-confirmation-delete" title="Delete User">Delete User</a> 
                <a href="<?php echo $this->createUrl('user/Resetpassword', array('h' => AppHelper::two_way_string_encrypt($model->id))); ?>" class="user-neato-button neato-button requires-confirmation-reset-password" title="Reset Password">Reset Password</a>
            </div>
        </div>
    <?php } ?>

    <?php
    
    $chat_attribute = array(
                'label' => 'Asssociated Robots',
                'type' => 'raw',
                'value' => $html_string,
            );
    
    $cDetailAttribute = array(
            'email',
            'alternate_email',
            $chat_attribute,
        );
    
    if(Yii::app()->user->isAdmin){
        $cDetailAttribute = array(
            'email',
            'alternate_email',
            $chat_attribute,
            'chat_id',
            'chat_pwd',
        );
    }
    
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $model,
        'attributes' => $cDetailAttribute,
        'id' => 'user_profile_detail'
    ));
    ?>

    <div id="edit_user_profile_btn" class='neato-button_alt right' title="Edit">Edit</div>

    <div class="form update_user_form hide">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'update_user_data-form',
            'focus' => array($update_user, 'alternate_email'),
//	      'enableAjaxValidation'=>true,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
                ));
        ?>

        <div class="row">
            <?php echo $form->labelEx($update_user, 'name', array('class' => 'update_user_lable')); ?>
            <?php echo $form->textField($update_user, 'name', array('size' => 30, 'cols' => 128, 'tabindex' => 2, 'class' => 'update_user_input')); ?>
            <?php echo $form->error($update_user, 'name', array('class' => 'prepend-4 errorMessage')); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($update_user, 'alternate_email', array('class' => 'update_user_lable')); ?>
            <?php echo $form->textField($update_user, 'alternate_email', array('size' => 30, 'cols' => 128, 'tabindex' => 1, 'class' => 'update_user_input')); ?>
            <?php echo $form->error($update_user, 'alternate_email', array('class' => 'prepend-4 errorMessage')); ?>
        </div>
        
        <?php if(Yii::app()->user->isAdmin) { ?>
            
            <div class="row" style="height: 40px;">
                <?php echo $form->labelEx($update_user, 'is_validated', array('class' => 'update_user_lable', 'style' => 'margin-top: 2px;')); ?>
                <?php // echo $form->textField($update_user, 'is_validated', array('size' => 30, 'cols' => 128, 'tabindex' => 2, 'class' => 'update_user_input')); ?>
                
                <input type="radio" name="is_validated" class="left" <?php if($update_user->is_validated == 1){ ?> checked="checked" <?php } ?> value="1" >
                <label class="update_user_lable update_user_radio">Yes</label>
                
                <input type="radio" name="is_validated" class="left" <?php if($update_user->is_validated == 0){ ?> checked="checked" <?php } ?> value="0" >
                <label class="update_user_lable update_user_radio">No</label>
                
                <?php echo $form->error($update_user, 'is_validated', array('class' => 'prepend-4 errorMessage')); ?>
                <br/>
            </div>
            
        <?php }?>
        
        <input id="update_user_data_flag" type="hidden" name="update_user_data_flag" value ="N">
        
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
            
            $('.update_user_form').hide();
            $('#user_profile_detail').show();
            $('#edit_user_profile_btn').show();
            
        });
        
    });
    
function hideUserProfile () {
    
    $('.update_user_form').show();
    $('#user_profile_detail').hide();
    $('#edit_user_profile_btn').hide();
    
}


</script>
