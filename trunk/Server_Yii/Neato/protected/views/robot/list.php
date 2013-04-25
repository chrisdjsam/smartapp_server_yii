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
					<th style="width: 7%;" title="Grid" class='pretty-table-center-th'>Grid</th>
					<th style="width: 7%;" title="Map" class='pretty-table-center-th'>Map</th>
					<th style="width: 7%;" class='pretty-table-center-td'></th>
				</tr>
			</thead>
			<tbody>
				
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
