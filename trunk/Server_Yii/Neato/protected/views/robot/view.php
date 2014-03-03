<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Robot Information - ' . Yii::app()->name;

$this->breadcrumbs=array(
		'Robots'=>array('index'),
		$model->name,
);
$user_role_id = Yii::app()->user->UserRoleId;
$html_string = '';
$associated_users_array = array();
if ($model->usersRobots){
	$is_first_user = true;
	$html_string = '';
	foreach($model->usersRobots as $value){
		if(!$is_first_user){
			$html_string .= ",&nbsp;";
		}
		$is_first_user = false;
		$associated_users_array[] = $value->idUser->email;
		$html_string .= "<a class='qtiplink' title='View details of (".$value->idUser->email.")' rel='".$this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."' href='".$this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."'>".$value->idUser->email."</a>";
	}
}

$can_send_start_and_stop_command = false;
$isAdmin = Yii::app()->user->isAdmin;
if($isAdmin || in_array(Yii::app()->user->email, $associated_users_array)){
	$can_send_start_and_stop_command = true;
}
?>


<fieldset
	class='data-container static-data-container'>
	<legend>
		Robot Information for
		<?php echo $model->serial_number; ?>
	</legend>
	<?php if($user_role_id !== '2'){ ?>
		<p class="list_details">This page shows the robot's basic, schedule data.</p>
	<?php } ?>
	
	<!-- 	<div class="action-button-container"> -->
	<!--<a href="<?php echo $this->createUrl('robot/delete',array('rid'=>AppHelper::two_way_string_encrypt($model->id)))?>" -->
	<!-- 				title="Delete robot" class="neato-button delete-single-item">Delete</a>  -->
	<!-- 	</div> -->
        <div class="action-button-container">
<!--         'Health check button is hide temp through css' -->
        		<?php 
                    print '<a href="#" id="is-robot-alive" class="neato-button" title="Is robot alive" robot-serail-no = "'.$model->serial_number.'">Health Check</a>';
                ?>
        
                <?php 
                    print '<a href="'.$this->createUrl('/robot/update',array('h'=>AppHelper::two_way_string_encrypt($model->id))).'" class="neato-button" title="Edit" robot "'.$model->serial_number.'">Edit</a>';
                ?>
        </div>
        <br />
	<div class="robot-data-table-heading">Basic Information</div>
	<?php
        
        $online_status = ' (OFFLINE)';
        if($isOnline == 1){
            $online_status = ' (ONLINE)';
        } else if($isOnline == 3) {
            $online_status = ' (VIRTUALLY ONLINE)';
        }
        
        $robot_type = isset($model->robotRobotTypes->robotType) ? $model->robotRobotTypes->robotType->name . ' (' . $model->robotRobotTypes->robotType->type . ')' : '';
        
        $created_on = 'Unavailable';
        if(!is_null($model->created_on)){
            $created_on = date("Y-m-d H:i:s", $model->created_on);;
        } 
        
        if($user_role_id !== '2'){
			$this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'attributes'=>array(
							'serial_number',
							'name',
							array(
									'label' =>'Associated Users',
									'type'=>'raw',
									'value' => $html_string,
							),
							array(
									'label' =>'Chat ID',
									'type'=>'raw',
									'value' => $model->chat_id . $online_status,
							),
							'chat_pwd',
							array(
									'label' =>'Robot Type',
									'type'=>'raw',
									'value' => $robot_type,
							),
							array(
									'label' =>'Last Pinged',
									'type'=>'raw',
									'value' => $last_ping,
							),
							array(
									'label' =>'Sleep Time',
									'type'=>'raw',
									'value' => $sleep_lag_time['sleep_time'] . ' seconds',
							),
							array(
									'label' =>'Wakeup Time',
									'type'=>'raw',
									'value' => $sleep_lag_time['lag_time'] . ' seconds',
							),
							array(
									'label' =>'Created on',
									'type'=>'raw',
									'value' => $created_on,
							),
					),
			));

		}else{
			$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				'serial_number',
				'name',
				array(
						'label' =>'Associated Users',
						'type'=>'raw',
						'value' => $html_string,
				),
				array(
						'label' =>'Status',
						'type'=>'raw',
						'value' => $online_status,
				),
				array(
						'label' =>'Last Pinged',
						'type'=>'raw',
						'value' => $last_ping,
				),
				
				array(
						'label' =>'Created on',
						'type'=>'raw',
						'value' => $created_on,
				),
				),
			));

		}
        
        
        
	 ?>
	<?php
	if($user_role_id !== '2'){
		if($can_send_start_and_stop_command){
		?>
		<hr class="robot-data-table-separator">
		<div class="robot-data-table-heading">Send Commands to the Robot</div>
		<p class="list_details">
			Click Start Cleaning Button to send start cleaning command to this
			robot. <br />Click Stop Cleaning Button to send stop cleaning command
			to this robot.
		</p>
	        
		<div class="action-button-container send-to-base-command_btn">
			<a class="send-to-base-command neato-button neato-button-large"
	                        href=<?php echo $this->createUrl('api/Robot/SendToBaseCommand',array('chat_id'=>AppHelper::two_way_string_encrypt($model->chat_id)))?>
				title="Send to Base">Send to Base</a>
		</div>
	
		<div class="action-button-container send-stop-command_btn hide">
			<a class="send-stop-command neato-button neato-button-large"
				href=<?php echo $this->createUrl('api/Robot/SendStopCommand',array('chat_id'=>AppHelper::two_way_string_encrypt($model->chat_id)))?>
				title="Stop Cleaning">Stop Cleaning</a>
		</div>
	
		<div class="action-button-container send-start-command_btn">
			<a class="send-start-command neato-button neato-button-large"
				href=<?php echo $this->createUrl('api/Robot/SendStartCommand',array('chat_id'=>AppHelper::two_way_string_encrypt($model->chat_id)))?>
				title="Start Cleaning">Start Cleaning</a>
		</div>
		<?php }}?>

		<?php if($user_role_id !== '2'){?>
			<hr class="robot-data-table-separator">
			<div id = "schedule_section" class="robot-data-table-heading">Schedule Data</div>
			<p class="list_details">
				<?php if($model->doesScheduleExist()) {	?>
		
				<?php if ($isAdmin){?>
				Click on add to add a robot schedule.<br /> Click on delete to delete
				a specific robot schedule.<br />
				<?php }?>
				Click on the XML file links to see a quick view.<br /> Click on the
				Binary data file links to see a quick view.<br /> Click on the
				download button inside the quick view to download it to your desktop.<br />
				<?php }else{?>
				<?php if ($isAdmin){?>
				Click Add Button to add robot schedule.
				<?php }else{ ?>
				Administrative privileges required to add robot schedule.
				<?php }
		} ?>
			</p>
			<?php if ($isAdmin){ ?>
			<div class="action-button-container">
				<a
					rel="<?php echo $this->createUrl('robotSchedule/add',array('sr_no'=>AppHelper::two_way_string_encrypt($model->serial_number), 
																		  'id_robot'=>AppHelper::two_way_string_encrypt($model->id),
																			))?>"
					title="Add robot Schedule"
					class="neato-button qtipPopuplink neato-button-large">Add Schedule</a>
			</div>
			<?php }
			if($model->doesScheduleExist()) {
			?>
		
			<div class="robot-data-table-container">
				<table class="pretty-table robot-schedule-table">
					<thead>
						<tr>
							<th style="width: 38px;" title="ID" class='pretty-table-center-th'>ID</th>
							<th title="Type" class='pretty-table-center-th'>Type</th>
							<th title="XML link">XML</th>
							<th title="Binary data">Binary data</th>
							<th title="XML Version number" class='pretty-table-center-th'>XML
								version</th>
							<th title="Binary data Version" class='pretty-table-center-th'>Binary
								data version</th>
							<?php if ($isAdmin){?>
							<th class='pretty-table-center-th'></th>
							<th class='pretty-table-center-th'></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($model->robotSchedules as $schedule){?>
		
						<tr>
							<td class='pretty-table-center-td'><?php echo $schedule->id;?></td>
							<td class='pretty-table-center-td'><?php echo $schedule->type;?></td>
							<td><a class='qtipPopuplink'
								title="Xml Schedule <?php echo $schedule->id;?> for Robot <?php echo $model->serial_number;?>"
								rel=<?php echo $this->createUrl('robotSchedule/PopupXmlview',array('h'=>AppHelper::two_way_string_encrypt($schedule->id)))?>
								href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('schedule'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-schedule_xml-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($schedule->id)))?>"
								target="_blank"><?php echo $schedule->xml_data_file_name;?> </a></td>
							<td><a class='qtipPopuplink'
								title="Blob Schedule <?php echo $schedule->id;?> for Robot <?php echo $model->serial_number;?>"
								rel=<?php echo $this->createUrl('robotSchedule/PopupBlobview',array('h'=>AppHelper::two_way_string_encrypt($schedule->id)))?>
								href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('schedule'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-schedule_blob-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($schedule->id)))?>"
								target="_blank"><?php echo $schedule->blob_data_file_name;?> </a>
							</td>
							<td class='pretty-table-center-td'><?php echo $schedule->XMLDataLatestVersion;?>
							</td>
							<td class='pretty-table-center-td'><?php echo $schedule->BlobDataLatestVersion;?>
							</td>
							<?php if ($isAdmin){?>
							<td class='pretty-table-center-td'><a
								rel=<?php echo $this->createUrl('robotSchedule/update',array('sr_no'=>AppHelper::two_way_string_encrypt($model->serial_number), 
										'id_robot'=>AppHelper::two_way_string_encrypt($model->id),
										'schedule_id'=>AppHelper::two_way_string_encrypt($schedule->id),
		
										))?>
								title="Edit robot schedule <?php echo $model->serial_number?>"
								class="qtipPopuplink  look-like-a-link ">edit</a>
							</td>
							<td class='pretty-table-center-td'>
		                                            <div class="delete-single-robot-schedule look-like-a-link " href="<?php echo $this->createUrl('api/RobotSchedule/deleteSchedule',array('h'=>AppHelper::two_way_string_encrypt($schedule->id)))?>" title="Delete robot schedule <?php echo $schedule->id?>">delete</div>
							</td>
							<?php }?>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php }else{?>
			<p class="noData">Robot schedule not available</p>
			<?php }?>
		<?php }?>			

	<input type="hidden" id = "scroll_section" value="<?php echo $scroll_to?>"/>
        <input type="hidden" id = "command_check_time_limit" value="<?php echo Yii::app()->params['command_check_time_limit']?>"/>
        <input type="hidden" id = "time_limit_for_check_robot_avail" value="<?php echo Yii::app()->params['time_limit_for_check_robot_avail']?>"/>
        <input type="hidden" id = "view_robot_serial_number" value="<?php echo $model->serial_number; ?>"/>
</fieldset>

<script>
$(window).load(function () {
	var section = "#" + $('#scroll_section').attr('value');
	location.hash = section;
	});
 
 $(document).ready(function(){
    currentRobotStatus('<?php echo $model->serial_number; ?>');
 });


</script>