<?php
/* @var $this UserController */
/* @var $model User */


$this->pageTitle='User Validation - ' . Yii::app()->name;

?>
<fieldset class='data-container static-data-container'>
	<div class="validated_email_page">
		<?php if($is_user_active == 'Y') {
			?>
		<h2>Your email address is activated successfully.</h2>
		<?php
		} else {
			?>
		<h2>Activation Failed due to some problem. please try again.</h2>
		<?php
		}
		?>
	</div>
</fieldset>
