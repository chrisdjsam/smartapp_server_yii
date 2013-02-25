<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Robots - '.Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'List',
);

?>
<fieldset class='data-container static-data-container'>
	<legend>Robots</legend>

	<p class="list_details">
		All the available robots are listed below.<br /> You can
		view robot information by clicking on the serial number.<br /> Click
		on edit to update a specific robot information. <br /> You can also
		select a robot and click on delete button to delete a robot.<br />
		Please note that deleting a robot would also delete the user-robot
		associations for that specific robot.
	</p>
	<form action="<?php echo $this->createUrl('api/robot/deleteRobot') ?>"
		method="POST" id="robotsList">
		<div class="action-button-container">
			<a href="<?php echo $this->createUrl('robot/add')?>"
				title="Add robot" class="neato-button">Add</a>
				</div>
			<div class="action-button-container">	 
				<input type='button' value='Delete' id='deleteSelected'
				class='deleteSelected neato-button' title="Delete robot" />
		</div>
		<table class="pretty-table robot-table">
			<thead>
				<tr>
					<th style="width: 7%;" title="Select" class='pretty-table-center-th'>Select</th>
					<th style="width: 12%;" title="Serial Number">Serial Number</th>
					<th style="width: 44%;" title="Associated Users">Associated Users</th>
					<th style="width: 9%;" title="Schedule" class='pretty-table-center-th'>Schedule</th>
					<th style="width: 7%;" title="Atlas" class='pretty-table-center-th'>Atlas</th>
					<th style="width: 7%;" title="Grid_Image" class='pretty-table-center-th'>Grid</th>
					<th style="width: 7%;" title="Map" class='pretty-table-center-th'>Map</th>
					<th style="width: 7%;" class='pretty-table-center-td'></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($robot_data as $robot){?>
				<tr>
					<td class='pretty-table-center-td'><input type="checkbox" name="chooseoption[]"
						value="<?php echo $robot->id;?>" class='choose-option'>
					</td>
					<td><a
						rel=<?php echo $this->createUrl('robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						class='qtiplink robot-qtip'
						title="View details of (<?php echo $robot->serial_number?>)"><?php echo $robot->serial_number?>
					</a>
					</td>
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
									?> <?php }
						 	echo $html_string;
						}
						?>
					</td>
					<td class='pretty-table-center-td'>
					<?php if ($robot->doesMapExist()){ ?>
					
					<a	href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'map_section'))?>
						title="View map details of robot (<?php echo $robot->serial_number?>)"> Yes </a>
					
					<?php }	?>
					</td>
					<td class='pretty-table-center-td'><?php
					if ($robot->doesScheduleExist()){?>
					
					<a	href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'schedule_section'))?>
						title="View schedule details of robot (<?php echo $robot->serial_number?>)"> Yes </a>
					
					<?php }	?>
					</td>
					<td class='pretty-table-center-td'><?php
					if ($robot->doesAtlasExist()){?>
					
					<a	href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'atlas_section'))?>
						title="View atlas details of robot (<?php echo $robot->serial_number?>)"> Yes </a>
					
					<?php }	?>
					</td>
					<td class='pretty-table-center-td'><?php
					if ($robot->doesAtlasExist() && $robot->robotAtlas->doesGridImageExist()){?>
					
					<a	href=<?php echo $this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'atlas_section'))?>
						title="View atlas details of robot (<?php echo $robot->serial_number?>)"> Yes </a>
					
					<?php }	?>
					</td>
					<td class='pretty-table-center-td'><a
						href=<?php echo $this->createUrl('robot/update',array('h'=>AppHelper::two_way_string_encrypt($robot->id)))?>
						title="Edit robot <?php echo $robot->serial_number?>">edit</a>
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
               if(confirm("Are you sure you want to delete selected robots?")){
                       $('#robotsList').submit();
               }
       }else{
               alert('Select at least one row to delete.');
       }
})
</script>
