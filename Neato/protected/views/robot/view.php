<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Robot Information - ' . Yii::app()->name;

$this->breadcrumbs=array(
		'Robots'=>array('index'),
		$model->name,
);
?>
<fieldset
	class='data-container static-data-container'>
	<legend>
		Robot Information for
		<?php echo $model->serial_number; ?>
	</legend>

	<p class="list_details">This page shows the robot's basic, map and
		schedule related data.</p>
	<!-- 	<div class="action-button-container"> -->
	<!--<a href="<?php echo $this->createUrl('robot/delete',array('rid'=>AppHelper::two_way_string_encrypt($model->id)))?>" -->
	<!-- 				title="Delete robot" class="neato-button delete-single-item">Delete</a>  -->
	<!-- 	</div> -->
	<hr class="robot-data-table-separator">
	<div class="robot-data-table-heading">Basic Information</div>
	<?php
	$html_string = '';
	if ($model->usersRobots){
		$is_first_user = true;
		$html_string = '';
	 foreach($model->usersRobots as $value){
	 	if(!$is_first_user){
	 		$html_string .= ",&nbsp;";
	 	}
	 	$is_first_user = false;
	 	$html_string .= "<a class='qtiplink' title='View details of (".$value->idUser->email.")' rel='".$this->createUrl('user/userprofilepopup',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."' href='".$this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."'>".$value->idUser->email."</a>";
	 }
	}
	?>
	<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
					'serial_number',
		'name',
					array(
							'label' =>'Asssociated Users',
							'type'=>'raw',
							'value' => $html_string,
					),
		'chat_id',
		'chat_pwd',
	),
	)); ?>


	<?php
	if($model->doesMapExist()) {
	?>
	<hr class="robot-data-table-separator">
	<div class="robot-data-table-heading">Map Data</div>
	<p class="list_details">
		Click on delete to delete a specific robot map.<br /> Click on the XML
		file links to see a quick view.<br /> Click on the Binary data file
		links to see a quick view.<br /> Click on the download button inside
		the quick view to download it to your desktop.<br />
	</p>
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
					<th class='pretty-table-center-th'></th>
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
					<td class='pretty-table-center-td'><div
							class="delete-single-robot-map look-like-a-link "
							href=<?php echo $this->createUrl('api/RobotMap/deleteMap',array('h'=>AppHelper::two_way_string_encrypt($map->id)))?>
							title="Delete robot map <?php echo $map->id?>">delete</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	<?php
	if($model->doesScheduleExist()) {
	?>
	<hr class="robot-data-table-separator">
	<div class="robot-data-table-heading">Schedule Data</div>
	<p class="list_details">
		Click on delete to delete a specific robot schedule.<br /> Click on
		the XML file links to see a quick view.<br /> Click on the Binary data
		file links to see a quick view.<br /> Click on the download button
		inside the quick view to download it to your desktop.<br />
	</p>
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
					<th class='pretty-table-center-th'></th>
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
					<td class='pretty-table-center-td'><div
							class="delete-single-robot-schedule look-like-a-link "
							href=<?php echo $this->createUrl('api/RobotSchedule/deleteSchedule',array('h'=>AppHelper::two_way_string_encrypt($schedule->id)))?>
							title="Delete robot schedule <?php echo $schedule->id?>">delete</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>

</fieldset>

<script>
$('.delete-single-item').click(function(){
	if(confirm("Are you sure you want to delete robot?")){
    	return true;
    }else{
        return false;
    }
});
</script>
