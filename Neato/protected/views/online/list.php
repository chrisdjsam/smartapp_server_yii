<?php
/* @var $this OnlineController */
/* @var $model User */
/* @var $model Robot */
$this->pageTitle='Online Users And Robots- ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Online'=>array('index'),
		'List',
);
?>
<fieldset class='data-container static-data-container robot-online-fieldset'>
	<legend>Online Users</legend>

	<form action="<?php echo $this->createUrl('') ?>"
		method="POST" id="onlineUserList">
	<p class="list_details">
	<?php if(!empty($users_data)){?>
		All the online users are listed below.<br />
	<?php }else{?>
		No Users are online.
	<?php }?>	 
	</p>
	<?php if(!empty($users_data)){?>	
	<table class="pretty-table online-user-table">
			<thead>
				<tr>
					<th title="Name">Name</th>
					<th title="Email">Email</th>
					<th title="Chat ID">Chat ID</th>
					<th title="Associated Robots">Associated Robots</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users_data as $user){?>
				<tr>
					<td><?php echo($user->name);?></td>
					<td><a
						rel="<?php echo $this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($user->id)))?>"
						href="<?php echo $this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($user->id)))?>"
						class='qtiplink' title="View details of (<?php echo($user->email);?>)"><?php echo($user->email);?>
					</a>
					</td>
					<td><?php echo($user->chat_id);?></td>
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
		<?php } ?>
</form>
</fieldset>
<br/><br/>
<fieldset class='data-container static-data-container robot-online-fieldset'>
	<legend>Online Robots</legend>

	<form action="<?php echo $this->createUrl('') ?>"
		method="POST" id="onlineUserList">
	<p class="list_details">
	<?php if(!empty($robot_data)){?>
		All the online robots are listed below.<br /> 
		<?php }else{?>
		No Robots are online.<br />
		<?php }?>
	</p>
	<?php if(!empty($robot_data)){?>
	<table class="pretty-table online-robot-table">
			<thead>
				<tr>
					<th style="width: 15%;" title="Serial Number">Serial Number</th>
					<th style="width: 15%;" title="Name">Name</th>
					<th style="width: 20%;" title="Chat ID">Chat ID</th>
					<th style="width: 40%;" title="Associated Users">Associated Users</th>
					
				</tr>
			</thead>
			<tbody>
				<?php foreach ($robot_data as $robot){?>
				<tr>
					
					<td><a
						rel=<?php echo $this->createUrl('robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						class='qtiplink robot-qtip'
						title="View details of (<?php echo $robot->serial_number?>)"><?php echo $robot->serial_number?>
					</a>
					</td>
					<td><?php echo($robot->name);?></td>
					<td><?php echo($robot->chat_id);?></td>
					<td class='multiple-item'><?php
					if ($robot->doesUserAssociationExist()){
						$is_first_user = true;
						$html_string = '';
						 foreach($robot->usersRobots as $value){
						 	if(!$is_first_user){
						 		$html_string .= ",";
						 	}
						 	$is_first_user = false;
						 	$html_string .= "<a class='single-item qtiplink' title='View details of (".$value->idUser->email.")' rel='".$this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."' href='".$this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."'>".$value->idUser->email."</a>"
									?> <?php 
						}
					 	echo $html_string;
					}
					?>
					</td>
	
				</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php }?>
	</form>
</fieldset>
<br/><br/>
<fieldset class='data-container static-data-container robot-online-fieldset'>
	<legend>Virtually Online Robots</legend>

	<form>
	<p class="list_details">
	<?php if(!empty($virtually_online_robots)){?>
		All the virtually online robots are listed below.<br /> 
		<?php }else{?>
		No robots are virtually online.<br />
		<?php }?>
	</p>
	<?php if(!empty($virtually_online_robots)){?>
	<table class="pretty-table virtually-online-robot-table">
			<thead>
				<tr>
					<th style="width: 15%;" title="Serial Number">Serial Number</th>
					<th style="width: 15%;" title="Name">Name</th>
					<th style="width: 20%;" title="Chat ID">Chat ID</th>
					<th style="width: 40%;" title="Associated Users">Associated Users</th>
					
				</tr>
			</thead>
			<tbody>
				<?php foreach ($virtually_online_robots as $robot){?>
				<tr>
					
					<td><a
						rel=<?php echo $this->createUrl('robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						class='qtiplink robot-qtip'
						title="View details of (<?php echo $robot->serial_number?>)"><?php echo $robot->serial_number?>
					</a>
					</td>
					<td><?php echo($robot->name);?></td>
					<td><?php echo($robot->chat_id);?></td>
					<td class='multiple-item'><?php
					if ($robot->doesUserAssociationExist()){
						$is_first_user = true;
						$html_string = '';
						 foreach($robot->usersRobots as $value){
						 	if(!$is_first_user){
						 		$html_string .= ",";
						 	}
						 	$is_first_user = false;
						 	$html_string .= "<a class='single-item qtiplink' title='View details of (".$value->idUser->email.")' rel='".$this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."' href='".$this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."'>".$value->idUser->email."</a>"
									?> <?php 
						}
					 	echo $html_string;
					}
					?>
					</td>
	
				</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php }?>
	</form>
</fieldset>