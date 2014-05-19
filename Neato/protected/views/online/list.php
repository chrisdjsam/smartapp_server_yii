<?php
/* @var $this OnlineController */
/* @var $model User */
/* @var $model Robot */
$this->pageTitle='Online Users And Robots- ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Online'=>array('index'),
		'List',
);
$baseURL = Yii::app()->baseUrl;
?>

<div>
	<span>Last refreshed at: <i><span class="last-refreshed-at"><?php print date('d-M-Y h:m:s:a') ." (" . date_default_timezone_get() . ")" ?></span></i></span>
	<a href="#" class='refresh_list_page neato-button_alt right' title="Refresh">Refresh</a>
</div>
<br/>
<br/>

<fieldset class='data-container static-data-container robot-online-fieldset'>
	<legend>Online Robots</legend>
	<form action="<?php echo $this->createUrl('') ?>" method="POST" id="onlineRobotList">
		<p class="robot_list_details list_details">No robots are online.</p>
		<table class="pretty-table online-robot-table">
			<thead>
				<tr>
					<th style="width: 15%;" title="Serial Number">Serial Number</th>
					<th style="width: 20%;" title="Name">Name</th>
					<th style="width: 20%;" title="Chat ID">Chat ID</th>
					<th style="width: 35%;" title="Associated Users">Associated Users</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
</fieldset>
<br />
<br />

<fieldset class='data-container static-data-container robot-online-fieldset'>
	<legend>Online Users</legend>
	<form action="<?php echo $this->createUrl('') ?>" method="POST" id="onlineUserList">
		<p class="user_list_details list_details">No users are online.</p>
		<table class="pretty-table online-user-table">
			<thead>
				<tr>
					<th style="width: 15%;" title="Name">Name</th>
					<th style="width: 20%;" title="Email">Email</th>
					<th style="width: 20%;" title="Chat ID">Chat ID</th>
					<th style="width: 35%;" title="Associated Robots">Associated Robots</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
</fieldset>
<br />
<br />

<script>
    $(document).ready(function(){
    	reset_dt_view();
		loadOnlineRobots();
        loadOnlineUsers();
		refreshDataGrid();
    });

    function loadOnlineUsers(){
        var baseURL = '<?php echo $baseURL; ?>';
        var handle = 'online-user-table';
        var length = 25;
        var url = '<?php echo $baseURL . '/online/onlineUsersDataTable' ?>';
        var colomns_to_disable_sort = [3];
        var default_sorting = [ 0, 'desc' ];
        dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting, 'updateUserFieldset');

    }

    function loadOnlineRobots(refresh){
        var baseURL = '<?php echo $baseURL; ?>';
        var handle = 'online-robot-table';
        var length = 25;
        var url = '<?php echo $baseURL . '/online/onlineRobotsDataTable' ?>';
        var colomns_to_disable_sort = [3];
        var default_sorting = [ 0, 'desc' ];
        dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting, 'updateRobotFieldset');
    }

    function updateUserFieldset(data){
        user_list_details = "All the online users are listed below."
        if(!data['aaData'].length){
            user_list_details = "No users are online."
        }
        $(".user_list_details").html(user_list_details);
    }

    function updateRobotFieldset(data){
        robot_list_details = "All the online robots are listed below."
        if(!data['aaData'].length){
   		    robot_list_details = "No robots are online."
        }
        $(".robot_list_details").html(robot_list_details);
    }

    function refresh_time(data){
        $('.last-refreshed-at').html(data['time'])
    }

    function refreshDataGrid(){
    	$('.refresh_list_page').click(function(){
			$.ajax({
				type : 'POST',
				url : app_base_url + '/online/refreshDataTable',
				dataType : 'jsonp',
				success : function(r) {
					hideWaitDialog();
					refresh_time(r);
					reset_dt_view();
		    		loadOnlineRobots();
		            loadOnlineUsers();
				},
				error : function(r) {
					hideWaitDialog();
				},
				beforeSend : function() {
					showWaitDialog();
				},
			});
        });
     }
</script>
