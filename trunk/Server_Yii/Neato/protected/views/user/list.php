<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle='Users - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Users'=>array('index'),
		'List',
);
?>
<fieldset class='data-container static-data-container'>
	<legend>Users</legend>

	<p class="list_details">
		All the users are listed below.<br /> You can view user profile by
		clicking on the email of a specific user.<br /> You can view robot
		information by clicking on the associated robots serial number.<br />
		You can also select a user and click on delete button to delete a
		user.<br /> Please note that deleting a user would also delete the
		user-robot associations for that specific user.<br />
	</p>

	<form action="<?php echo $this->createUrl('user/delete') ?>"
		method="POST" id="userList">
		<div class="action-button-container">
			<a href="<?php echo $this->createUrl('user/add')?>" title="Add user"
				class="neato-button">Add</a> <input type='button' value='Delete'
				id='deleteSelected' class='deleteSelected neato-button'
				title="Delete user" />
		</div>
		<table class="pretty-table user-table">
			<thead>
				<tr>
					<th style="width: 38px;" title="Select" class='pretty-table-center-th'>Select</th>
					<th title="Email">Email</th>
					<th title="Name">Name</th>
					<th title="Associated Robots">Associated Robots</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users_data as $user){?>
				<tr>
					<td class='pretty-table-center-td'><?php 
					if($user->is_admin == '0'){?> <input type="checkbox"
						name="chooseoption[]" value="<?php echo $user->id;?>"
						class='choose-option'> <?php } else{
							echo("Admin");
						}?></td>
					<td><a
						rel="<?php echo $this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($user->id)))?>"
						href="<?php echo $this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($user->id)))?>"
						class='qtiplink' title="View details of (<?php echo($user->email);?>)"><?php echo($user->email);?>
					</a>
					</td>
					<td><?php echo($user->name);?>
					</td>
					<td class='multiple-item'><?php if ($user->doesRobotAssociationExist()){ 
						$is_first_robot = true;
						$html_string = '';
						foreach($user->usersRobots as $value){
		 	if(!$is_first_robot){
		 		$html_string .= ",";
		 	}
		 	$is_first_robot = false;
		 	$html_string .= "<a class='single-item qtiplink robot-qtip' title='View details of (".$value->idRobot->serial_number.")' rel='".$this->createUrl('robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($value->idRobot->id)))."' href='".$this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($value->idRobot->id)))."'>".$value->idRobot->serial_number."</a>";
				}
		 	echo $html_string;
					}
					?>
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
               if(confirm("Are you sure you want to delete selected users?")){
                       $('#userList').submit();
               }
       }else{
               alert('Select at least one row to delete.');
       }
})
</script>
