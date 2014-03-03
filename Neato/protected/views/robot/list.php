<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Robots - '.Yii::app()->name;
$this->breadcrumbs=array(
		'Robots'=>array('index'),
		'List',
);

?>
<?php $userRole = Yii::app()->user->UserRoleId; ?>
<fieldset class='data-container static-data-container'>
		<legend>
	<?php if($userRole != '2'){?>
		Robots
	<?php
	}else{ 
	?>
	Search Robot
	<?php
	} 
	?>
	</legend>

	<p class="list_details">
		<?php if($userRole != '2'){?>
			All the available robots are listed below.<br />
		<?php }?>
		<?php if($userRole == '2'){?>
		You can search a specific robot by typing in serial number and then clicking on search button.<br />
		<?php }?>		 
				 
		You can view robot information by clicking on the serial number.<br />
		<?php if($userRole != '2'){?>
			All the available robots are listed below.<br />
		<?php }?>		 
		Click on edit to update a specific robot information. <br /> 
		<?php if($userRole != '2'){?>
			You can also select a robot and click on delete button to delete a robot.<br />
			Please note that deleting a robot would also delete the user-robot
			associations for that specific robot.
		<?php }?>
	</p>
	
	<?php if($userRole == '2'){?>
		<div class="search-box">
			<input class="search-text-input" type="text" aria-controls="" / ><span><button class="btn btn-small search-button for-robot">Search</button></span>
		</div>
	<?php }?>
	
	<form action="<?php echo $this->createUrl('api/robot/deleteRobot') ?>"
		method="POST" id="robotsList">
			<?php if($userRole != '2'){?>
				<div class="action-button-container">
					<a href="<?php echo $this->createUrl('robot/add')?>"
						title="Add robot" class="neato-button">Add</a>
				</div>
				<div class="action-button-container">	 
					<input type='button' value='Delete' id='deleteSelected'
					class='deleteSelected neato-button' title="Delete robot" />
				</div>
			<?php }?>
		<table class="pretty-table robot-table">
			<thead>
				<tr>
					<?php if($userRole != '2'){?>
						<th style="width: 7%;" title="Select" class='pretty-table-center-th' class="pretty-table-center-td">Select</th>
					<?php }?>
					<th class="pretty-table-center-td" title="Serial Number">Serial Number</th>
					<?php if($userRole != '2'){?>
                    	<th style="width: 12%;" title="Robot Type" class="pretty-table-center-td">Robot Type</th>
                    <?php }?>
					<th  title="Associated Users" class="pretty-table-center-td">Associated Users</th>
					<?php if($userRole != '2'){?>
						<th style="width: 9%;" title="Schedule" class='pretty-table-center-th'>Schedule</th>
						<th style="width: 5%;" class='pretty-table-center-td'></th>
					<?php }?>						
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</form>
</fieldset>

<script>
var user_role_id = '<?php print Yii::app()->user->UserRoleId;?>'
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
