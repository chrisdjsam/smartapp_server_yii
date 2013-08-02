<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Robot Information - ' . Yii::app()->name;

$this->breadcrumbs=array(
		'Robots'=>array('index'),
		$model->name,
);

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

	<p class="list_details">This page shows the robot's basic, map,
		schedule and atlas related data.</p>
	<!-- 	<div class="action-button-container"> -->
	<!--<a href="<?php echo $this->createUrl('robot/delete',array('rid'=>AppHelper::two_way_string_encrypt($model->id)))?>" -->
	<!-- 				title="Delete robot" class="neato-button delete-single-item">Delete</a>  -->
	<!-- 	</div> -->
        <div class="action-button-container">
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
			)); ?>
	<?php
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
	<?php }?>

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


	<hr class="robot-data-table-separator">
	<div id = "atlas_section" class="robot-data-table-heading">Atlas Data</div>
	<p class="list_details">
		<?php
		if($model->doesAtlasExist()) {
	?>
		<?php if ($isAdmin){?>
		Click Edit Metadata to edit existing atlas with uploading a updated
		XML. <br> While editing you also have options to delete all grid
		images or delete this atlas. <br /> 
		<?php }?>
		Click View Metadata to view existing atlas xml file.<br/> <br/>
		
		<?php if($model->robotAtlas->doesGridImageExist()) {	?>
		<?php if ($isAdmin){?>	
		Click on add to add a specific atlas grid image.<br /> Click on delete
		to delete a specific atlas grid image.<br /> 
		<?php }?>
		Click on the blob file links to see a quick view.<br /> Click on the download button inside
		the quick view to download it to your desktop.<br />

		<?php }else{?>
		<?php if ($isAdmin){?>
			Click Add Button to add atlas grid image.
		<?php } else {?>
			Administrative privileges required to atlas grid image.
		<?php }}?>

		<?php }else{?>
		<?php if ($isAdmin){?>
		Click Add Button to add atlas.
		<?php }else{?>
		Administrative privileges required to add robot atlas.
		<?php }?>
	</p>
	<?php }?>

	<div class="action-button-container">
		<?php if($model->doesAtlasExist() ){ ?>

		<a class='qtipPopuplink neato-button neato-button-large'
			title="Robot Atlas<?php echo $model->robotAtlas->id;?> for Robot <?php echo $model->serial_number;?>"
			rel=<?php echo $this->createUrl('robotAtlas/PopupXmlview',array('h'=>AppHelper::two_way_string_encrypt($model->robotAtlas->id)))?>
			href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('atlas'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-atlas-xml-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($model->robotAtlas->id)))?>"
			target="_blank">View Metadata </a> 
		
		<?php if ($isAdmin){?>	
		<a
			rel="<?php echo $this->createUrl('robotAtlas/Update',array('id_robot'=>AppHelper::two_way_string_encrypt($model->id)))?>"
			title="Edit Metadata"
			class="neato-button neato-button-large qtipPopuplink ">Edit Metadata</a>

		<a
			rel="<?php echo $this->createUrl('GridImage/add',array('id_robot'=>AppHelper::two_way_string_encrypt($model->id)))?>"
			title="Add atlas grid image"
			class="neato-button neato-button-large qtipPopuplink ">Add Grid Image</a>
		<?php }?>

		<?php } else {?>
		<?php if ($isAdmin){?>
		<a
			rel="<?php echo $this->createUrl('robotAtlas/add',array('id_robot'=>AppHelper::two_way_string_encrypt($model->id)))?>"
			title="Add robot Atlas" class="neato-button qtipPopuplink ">Add Atlas</a>
		<?php }?>
		<?php }?>

	</div>

	<?php	if($model->doesAtlasExist()) {	?>

	<?php if($model->doesAtlasExist() && $model->robotAtlas->doesGridImageExist()) {	?>
	<div class="robot-data-table-container">
		<table class="pretty-table grid-image-table">
			<thead>
				<tr>
					<th style="width: 20%;" title="ID" class='pretty-table-center-th'>Grid ID</th>
					<th style="width: 20%;" title="XML link">Atlas grid image</th>
					<th style="width: 15%;" title="XML Version number" class='pretty-table-center-th'>Blob version</th>
				<?php if ($isAdmin){?>		
					<th class='pretty-table-center-th'></th>
					<th class='pretty-table-center-th'></th>
				<?php }?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($model->robotAtlas->atlasGridImages as $atlasGridImage){?>
				<tr>

					<td class='pretty-table-center-td'><?php echo $atlasGridImage->id_grid;?>
					</td>
					<td><a class='qtipPopuplink'
						title="Grid image <?php echo $atlasGridImage->id_grid;?> for Robot <?php echo $model->serial_number;?>"
						rel=<?php echo $this->createUrl('gridImage/PopupBlobView',array('h'=>AppHelper::two_way_string_encrypt($atlasGridImage->id)))?>
						href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('atlasGridImage'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-atlas-blob-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($atlasGridImage->id)))?>"
						target="_blank"><?php echo $atlasGridImage->blob_data_file_name;?>
					</a></td>

					<td class='pretty-table-center-td'><?php echo $atlasGridImage->version;?>
					</td>
				<?php if ($isAdmin){?>
					<td class='pretty-table-center-td'><a
						rel=<?php echo $this->createUrl('gridImage/update',array('id_grid_image'=>AppHelper::two_way_string_encrypt($atlasGridImage->id),))?>
						title="Edit grid image <?php echo $atlasGridImage->id_grid?>"
						class="qtipPopuplink  look-like-a-link ">edit</a>
					</td>
					<td class='pretty-table-center-td'><div
							class="delete-single-grid-image look-like-a-link "
							href=<?php echo $this->createUrl('api/GridImage/delete',array('id_grid_image'=> AppHelper::two_way_string_encrypt($atlasGridImage->id)))?>
							title="Delete atlas grid image <?php echo $atlasGridImage->id_grid?>">delete</div>
					</td>
					<?php } ?>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<?php }else{?>
	<p class="noData">Atlas grid image not available</p>
	<?php }?>

	<?php }else{?>
	<p class="noData">Robot atlas not available</p>
	<?php }?>




	<hr class="robot-data-table-separator">
	<div id = "map_section"  class="robot-data-table-heading">Map Data (Deprecated)</div>
	<p class="list_details">
		<?php if($model->doesMapExist()) {	?>
		<?php if ($isAdmin){?>
		Click on add to add a new robot map.<br /> Click on delete to delete a
		specific robot map.<br />
		<?php }?>
		 Click on the XML file links to see a quick
		view.<br /> Click on the Binary data file links to see a quick view.<br />
		Click on the download button inside the quick view to download it to
		your desktop.<br />
		<?php }else{?>
		<?php if ($isAdmin){?>
		Click Add Button to add robot map.
		<?php }else{?>
		Administrative privileges required to add robot map.
		<?php }}?>
	</p>
	<?php if ($isAdmin){?>
	<div class="action-button-container">
		<a
			rel="<?php echo $this->createUrl('robotMap/add',array('sr_no'=>AppHelper::two_way_string_encrypt($model->serial_number), 
																  'id_robot'=>AppHelper::two_way_string_encrypt($model->id),
																	))?>"
			title="Add robot Map" class="qtipPopuplink  neato-button">Add Map</a>
	</div>
	<?php } ?>
	<?php
	if($model->doesMapExist()) {
	?>
	<div class="robot-data-table-container">
		<table class="pretty-table robot-map-table">
			<thead>
				<tr>
					<th style="width: 38px;" title="ID" class='pretty-table-center-th'>ID</th>
					<th title="XML link">XML</th>
					<th title="Binary data">Binary data</th>
					<th title="XML Version number" class='pretty-table-center-th'>XML
						version</th>
					<th title="Binary data Version" class='pretty-table-center-th'>Binary
						data version</th>
						<?php if ($isAdmin){?>
					<th class='pretty-table-center-th'></th>
					<th class='pretty-table-center-th'></th>
					<?php }?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($model->robotMaps as $map){?>
				<tr>
					<td class='pretty-table-center-td'><?php echo $map->id;?></td>
					<td><a class='qtipPopuplink'
						title="Xml Map <?php echo $map->id;?> for Robot <?php echo $model->serial_number;?>"
						rel=<?php echo $this->createUrl('robotMap/PopupXmlview',array('h'=>AppHelper::two_way_string_encrypt($map->id)))?>
						href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('map'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-xml-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($map->id)))?>"
						target="_blank"><?php echo $map->xml_data_file_name;?> </a></td>
					<td><a class='qtipPopuplink'
						title="Blob Map <?php echo $map->id;?> for Robot <?php echo $model->serial_number;?>"
						rel=<?php echo $this->createUrl('robotMap/PopupBlobview',array('h'=>AppHelper::two_way_string_encrypt($map->id)))?>
						href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('map'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-blob-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($map->id)))?>"
						target="_blank"><?php echo $map->blob_data_file_name;?> </a></td>
					<td class='pretty-table-center-td'><?php echo $map->XMLDataLatestVersion;?>
					</td>
					<td class='pretty-table-center-td'><?php echo $map->BlobDataLatestVersion;?>
					</td>
					<?php if ($isAdmin){?>
					<td class='pretty-table-center-td'><a
						rel=<?php echo $this->createUrl('robotMap/update',array('sr_no'=>AppHelper::two_way_string_encrypt($model->serial_number), 
								'id_robot'=>AppHelper::two_way_string_encrypt($model->id),
																		'map_id'=>AppHelper::two_way_string_encrypt($map->id)))?>
						"
						title="Edit robot Map <?php echo $map->id?>"
						class="qtipPopuplink  look-like-a-link ">edit</a>
					</td>
					<td class='pretty-table-center-td'><div
							class="delete-single-robot-map look-like-a-link "
							href=<?php echo $this->createUrl('api/RobotMap/deleteMap',array('h'=>AppHelper::two_way_string_encrypt($map->id)))?>
							title="Delete robot map <?php echo $map->id?>">delete</div>
					</td>
					<?php }?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }else{?>
	<p class="noData">Robot map not available</p>
	<?php }?>
	<input type="hidden" id = "scroll_section" value="<?php echo $scroll_to?>"/>
        <input type="hidden" id = "command_check_time_limit" value="<?php echo Yii::app()->params['command_check_time_limit']?>"/>
        <input type="hidden" id = "view_robot_serial_number" value="<?php echo $model->serial_number; ?>"/>
</fieldset>

<script>
$(window).load(function () {
	var section = "#" + $('#scroll_section').attr('value');
	location.hash = section;
	});
 
 $(document).ready(function(){
    currentRobotStatus(<?php echo $model->serial_number; ?>);
 });


</script>