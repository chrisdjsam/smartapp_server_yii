<?php
/* @var $this UsersRobotController */
/* @var $model UsersRobot */
$this->pageTitle='User-Robot Associations - '.Yii::app()->name;
$this->breadcrumbs=array(
		'Users Robots'=>array('index'),
		'List',
);
?>
<fieldset class='data-container static-data-container'>
	<legend>User-Robot Associations</legend>

	<p class="list_details">
		All the user-robot associations are listed below.<br /> You can also
		select a row and click on delete button to delete a user-robot
		associations.<br /> You can view user profile by clicking on the
		email.<br /> You can view robot information by clicking on the serial
		number.<br />

	</p>
		<form action="<?php echo $this->createUrl('usersRobot/delete') ?>"
		method="POST" id="usersRobotList">
		<div class="action-button-container">
			<a href="<?php echo $this->createUrl('usersRobot/add')?>"
				title="Add association" class="neato-button">Add</a> <input
				type='button' value='Delete' id='deleteSelected'
				class='deleteSelected neato-button' title="Delete association" />
		</div>
		<table class="pretty-table user-robot-table">
			<thead>
				<tr>
					<th style="width: 38px;" title="Select" >Select</th>
					<th title="User">User</th>
					<th title="Robot Serial Number" >Robot Serial Number</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users_robots as $user_robot){?>
				<tr>
					<td class='pretty-table-center-td'><input type="checkbox" name="chooseoption[]"
						value="<?php echo $user_robot->id;?>" class='choose-option'>
					</td>
					<td><a
						rel="<?php echo $this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($user_robot->idUser->id)))?>"
						href="<?php echo $this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($user_robot->idUser->id)))?>"
						class='qtiplink'
						title="View details of (<?php echo($user_robot->idUser->email);?>)"><?php echo($user_robot->idUser->email)?>
					</a>
					</td>
					<td><a
						rel="<?php echo $this->createUrl('robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($user_robot->idRobot->id)))?>"
						href="<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($user_robot->idRobot->id)))?>"
						class='qtiplink robot-qtip'
						title="View details of (<?php echo $user_robot->idRobot->serial_number?>)"><?php echo($user_robot->idRobot->serial_number);?>
					</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form>
</fieldset>
<script>
$('#deleteSelected').click(function(){
       var is_any_check_box_checked = false;
       $('.choose-option').each(function(index) {
               if ($(this).is(':checked')) {
                       is_any_check_box_checked = true;
               }
       });

       if(is_any_check_box_checked){
               if(confirm("Are you sure you want to delete selected user-robot associations ?")){
                       $('#usersRobotList').submit();
               }
       }else{
               alert('Select at least one row to delete.');
       }
})
</script>
