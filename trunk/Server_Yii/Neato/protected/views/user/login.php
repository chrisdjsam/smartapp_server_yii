<?php

$cs = Yii::app()->getClientScript();
$cs->registerScript('fb_permissions', 'var fb_permissions = "' . Yii::app()->params['fb-permissions'] . '";', CClientScript::POS_HEAD);
$cs->registerScript('app-redirect', 'var redirect_url = "' . Yii::app()->user->getReturnUrl() . '";', CClientScript::POS_HEAD);

$this->pageTitle='Login - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'login',
);
?>
<?php $is_wp_enabled = Yii::app()->params['is_wp_enabled'];
$register_url =  $this->createUrl('/user/register');
$forgot_pass = $this->createUrl('/user/forgotpassword');
$email = 'Email';
if($is_wp_enabled){
	$register_url = Yii::app()->params['wordpress_api_url'].'wp-login.php?action=register';
	$forgot_pass = Yii::app()->params['wordpress_api_url'].'wp-login.php?action=lostpassword';
	$email = 'Username';
}
?>
<label class='login-heading'>My Account</label>
<div class="form login-form">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'login-form',
			'clientOptions'=>array('validateOnSubmit'=>true,),
)); ?>
	<div class="section login_section">
		<div class="section_left">
			<h3>Returning customer?</h3>
			<div class="row">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email'); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password'); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<?php if(!Yii::app()->params['is_customize']){ ?>
			<div class="row rememberMe">
				<div class="remembe-me-check">
					<?php echo $form->checkBox($model,'rememberMe'); ?>
				</div>
				<div>
					<?php echo $form->label($model,'rememberMe',array("label" => "Remember me", "class" => "remember-me-label")); ?>
					<?php echo $form->error($model,'rememberMe'); ?>
				</div>
			</div>
			<?php } ?>
			<div class="row-buttons login_submit_btn">
				<?php echo CHtml::submitButton('Login', array('class'=>"neato-button",  "title" => "Login")); ?>
				<!--
				<span>
					<a class="forgot_link look-like-a-link" href="<?php //echo $forgot_pass; ?>" title="Forgot password">Forgot password?</a>
 					<br />
					<span id="resend_validation_email" class="forgot_link look-like-a-link" title="Resend validation email?">Resend validation
						email?</span>
				</span>
				-->
			</div>
			<!--
			<?php //if(!$is_wp_enabled){ ?>
			<div class="social_login_connect_with">
				<b>Or connect with </b>
			</div>
			<img alt="Facebook Login" src="<?php //echo Yii::app()->request->baseUrl."/images/facebook.png"?>"
				class='btn-facebook look-like-a-link' title="Facebook">
			<?php //}?>
			-->
		</div>
		<div class="section_right">
			<h3>New Customer?</h3>
			<p>
				Create an account and register your Neato now.
				<br />
				We'll send you a FREE filter.
			</p>
			<a class="neato-button" href="<?php print $register_url?>" title="Register">Register</a>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<!-- form -->
<div id="resend_validation_email_popup" title="Resend validation email" class="resend_validation_email_popup_class hide-me">
	<div class="device-entry-center-div">
		<div class="device-entry">
			<label for="Email" class="email_resend_label_style">
				<?php print $email ?>
				<span class="required">*</span>
			</label>
			<input type="text" value="" id="enter_user_email" name="email_to_send_validation_email" class="email_resend_input_style"
				tabindex="2" cols="128" size="30">
			<div id="User_email_em_" class="prepend-2 errorMessage hide-me"></div>
		</div>
		<div class="device-entry center-align-style">
			<input type="button" value="Send" name="send_email_validation" title="Send" class="neato-button"
				id="send_resend_email_validation">
			<input type="button" value="Cancel" name="cancel_resend_email_validation" title="Cancel" class="neato-button"
				id="cancel_resend_email_validation">
		</div>
	</div>
</div>
<script>

    $(document).ready(function(){
        var is_wp_enabled = '<?php print Yii::app()->params['is_wp_enabled']?>';
        $('#resend_validation_email').click(function() {

                $( "#resend_validation_email_popup" ).dialog({
                    width: 450,
                    position:['center',100],
                    modal: true
                });

                $('div#resend_validation_email_popup').siblings('div.ui-dialog-titlebar').css('background', '#DC4405');
                $('div#resend_validation_email_popup').siblings('div.ui-dialog-titlebar').css('color', '#FFFFFF');

        });

        $('#send_resend_email_validation').click(function(){

                var email = $('#enter_user_email').val();
                if(!is_wp_enabled){
                    if(!validateEmail(email)){
                        $('#User_email_em_').show();
                        $('#User_email_em_').html("Please enter valid email address.");
                        return;
                    }
                }

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->createUrl('/api/user/ResendValidationEmail')?>',
                    dataType: 'json',
                    data: {
                        email: email
                    },
                    success: function(r) {

                        hideWaitDialog();

                        if(r.status == 0){
                            generate_noty("success", r.result.message);
                        } else {
                            generate_noty("error", r.message);
                        }

                    },
                    error: function(r){
                        console.log(r);
                    },
                    beforeSend: function(){
                        close_popup();
                        showWaitDialog();
                    }

                });

        });

        $('#cancel_resend_email_validation').click(function(){
            close_popup();
        });

    });

    function close_popup() {
        $('#User_email_em_').hide();
        $('#resend_validation_email_popup').dialog('close');
    }

</script>
