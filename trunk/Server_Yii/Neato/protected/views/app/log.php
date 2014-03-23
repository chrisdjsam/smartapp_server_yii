<?php

$this->pageTitle = 'Web Service Log - ' . Yii::app()->name;
$baseURL = Yii::app()->baseUrl;
$this->breadcrumbs = array(
		'App' => array('index'),
		'Log',
);

?>
<fieldset class='data-container static-data-container'>
	<legend>Web Service Logs</legend>
	<p class="list_details">
		Web service log have been displayed below.
		<br />
		Click on 'Refresh' button to refresh the data.
		<br />
	</p>
	<div>
		<a href="#" id="refresh_ws_log_page" class='neato-button_alt right' title="Refresh">Refresh</a>
	</div>
	<table class="pretty-table web_service_log">
		<thead>
			<tr class="notification_datagrid">
				<th style="width: 10%;" class='pretty-table-center-th'>Remote Address</th>
				<th style="width: 15%;" class='pretty-table-center-th'>Method Name</th>
				<th style="width: 25%;" class='pretty-table-center-th text_transform_none'>Request Data</th>
				<th style="width: 25%;" class='pretty-table-center-th text_transform_none'>Response Data</th>
				<th style="width: 10%;" class='pretty-table-center-th text_transform_none'>Response Time in MS</th>
				<th style="width: 15%;" class='pretty-table-center-th'>Logged on</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</fieldset>
<script>

    $(document).ready(function(){

        var baseURL = '<?php echo $baseURL; ?>';
        var handle = 'web_service_log';
        var length = 25;
        var notification_history_url = '<?php echo $baseURL . '/app/webServiceLog' ?>';
        var colomns_to_disable_sort = [];
        var default_sorting = [ 5, 'desc' ];

        dataTableForAll(handle, length, notification_history_url, colomns_to_disable_sort, default_sorting, 'ws_log_datagrid');
        $('#refresh_ws_log_page').click(function(){
            notification_history_table(handle, length, notification_history_url, colomns_to_disable_sort, default_sorting);
        });

    });

</script>
