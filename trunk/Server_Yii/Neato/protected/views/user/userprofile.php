<?php
/* @var $this UserController */
/* @var $model User */

if (Yii::app()->user->id !==  $model->id){
	$this->pageTitle='Profile Details - ' . Yii::app()->name;
}else{
	$this->pageTitle='My Profile - ' . Yii::app()->name;
}

$this->breadcrumbs=array(
		'Users'=>array('index'),
		$model->name,
);
?>

<fieldset class='data-container static-data-container'>

	<?php
	$legend_message = "My Profile";
	if (Yii::app()->user->id !==  $model->id){
	$legend_message = 	"Profile details for $model->name";
}
?>
	<legend>
		<?php echo $legend_message; ?>
	</legend>
	<?php if (Yii::app()->user->id ==  $model->id){?>
	<p class="list_details">
		Please review your profile information.<br />
	</p>
	<?php }?>

	<?php 
	$html_string = '';
	if ($model->doesRobotAssociationExist()){
		$is_first_robot = true;
		$html_string = '';
		foreach($model->usersRobots as $value){
		 	if(!$is_first_robot){
		 		$html_string .= ",&nbsp";
		 	}
		 	$is_first_robot = false;
		 	$html_string .= "<a class='qtiplink robot-qtip' title='View details of (".$value->idRobot->serial_number.")' rel='".$this->createUrl('robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($value->idRobot->id)))."' href='".$this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($value->idRobot->id)))."'>".$value->idRobot->serial_number."</a>";
		 }
	}

	?>

	<?php if (Yii::app()->user->isAdmin && Yii::app()->user->id !==  $model->id){?>

	<p class="list_details">
		You can delete this user by clicking on delete user button.<br /> You
		can reset password for this user by clicking on reset password button.<br />
		Please note that deleting a user would also delete the user-robot
		associations for this specific user.<br /> Please note that resetting
		password for this user would reset the user's old password and send an
		email mentioning user's new password.<br />And user would not able to
		login using old password.<br />
	</p>

	<div class="action-button-container">
		<a
			href="<?php echo $this->createUrl('user/Delete',array('h'=>AppHelper::two_way_string_encrypt($model->id)));?>"
			class="user-neato-button neato-button requires-confirmation-delete"
			title="Delete User">Delete User</a> <a
			href="<?php echo $this->createUrl('user/Resetpassword',array('h'=>AppHelper::two_way_string_encrypt($model->id)));?>"
			class="user-neato-button neato-button requires-confirmation-reset-password"
			title="Reset Password">Reset Password</a>
	</div>
	<?php }?>

	<?php  $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
		'email',
		'name',
		array(
		'label' =>'Asssociated Robots',
		'type'=>'raw',
		'value' => $html_string,
		),
		'chat_id',
		'chat_pwd',
	),
	));
	?>
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
})
</script>
