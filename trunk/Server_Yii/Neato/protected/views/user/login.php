<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$cs = Yii::app()->getClientScript();
$cs->registerScript('fb_permissions', 'var fb_permissions = "' . Yii::app()->params['fb-permissions'] . '";', CClientScript::POS_HEAD);
$cs->registerScript('app-redirect', 'var redirect_url = "' . Yii::app()->user->getReturnUrl() . '";', CClientScript::POS_HEAD);

$this->pageTitle='Login - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'login',
);
?>

<label class='login-heading'>My Account</label>

<div class="form login-form">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'login-form',
			'enableAjaxValidation'=>true,
			//'enableClientValidation'=>true,
			'clientOptions'=>array('validateOnSubmit'=>true,),
			'focus'=>array($model,'email'),
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

			<div class="row rememberMe">
				<?php echo $form->checkBox($model,'rememberMe'); ?>
				<?php echo $form->label($model,'rememberMe',array("label" => "Remember me")); ?>
				<?php echo $form->error($model,'rememberMe'); ?>
			</div>

			<div class="row buttons">
				<?php echo CHtml::submitButton('Login', array('class'=>"neato-button",  "title" => "Login")); ?>
				<span> <a class="forgot_link look-like-a-link"
					href="<?php echo $this->createUrl('/user/forgotpassword')?>"
					title="Forgot password">Forgot password?</a>
				</span>
			</div>

			<div class="social_login_connect_with">
				<b>Or connect with </b>
			</div>
			<img alt="Facebook Login"
				src="<?php echo Yii::app()->request->baseUrl."/images/facebook.png"?>"
				class='btn-facebook look-like-a-link' title="Facebook">
		</div>

		<div class="section_right">
			<h3>New Customer?</h3>
			<p>
				Create an account and register your Neato now.<br />We'll send you a
				FREE filter.
			</p>
			<a class="neato-button"
				href="<?php echo $this->createUrl('/user/register')?>"
				title="Register">Register</a>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<!-- form -->
