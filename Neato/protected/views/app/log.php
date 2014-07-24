<?php

$this->pageTitle = 'Web Service Log - ' . Yii::app()->name;
$baseURL = Yii::app()->baseUrl;
$this->breadcrumbs = array(
		'App' => array('index'),
		'Log',
);
?>
<script
	type="text/javascript" language="javascript" src="http://datatables.net/release-datatables/media/js/jquery.js"></script>
<script
	type="text/javascript" language="javascript" src="http://datatables.net/release-datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript"
	language="javascript" src="http://datatables.net/release-datatables/extensions/ColVis/js/dataTables.colVis.js"></script>
<link
	rel="stylesheet" type="text/css" href="http://datatables.net/release-datatables/media/css/jquery.dataTables.css">
<link
	rel="stylesheet" type="text/css" href="http://datatables.net/release-datatables/extensions/ColVis/css/dataTables.colVis.css">
<script>
	var jqNC = jQuery.noConflict();
</script>
<style>
.container {
	max-width: 100%;
	width: 100%;
}

.page-footer {
	width: 100%;
}

.adminMenuUser {
	width: 60%;
}

.page-body {
	padding: 20px 10px;
	margin-bottom: 50px;
}

#content {
	padding: 20px 10px;
}

.ColVis {
	float: left !important;
}

.dataTables_filter input {
	height: 20px;
}

.list_details {
	padding-left: 5px;
	padding-top: 5px;
}

.app-log-settings {
	border: 1px solid #CCCCCC;
	padding-left: 5px;
	margin-top: 5px;
	margin-right: 5px;
	padding-top: 5px;
	padding-bottom: 5px;
}

.app-log-settings table {
	width: 50%;
	margin-bottom: 0;
	margin-left: 15px;
}

.app-log-info {
	padding-bottom: 5px;
}
</style>
<fieldset class='data-container static-data-container'>
	<legend>Web Service Logs</legend>
	<div class="list_details">
		Web service logs are displayed below.
		<br />
		Click on 'Refresh' button to refresh the data.
		<br />
		You can also click on "Show/hide columns" button to show/hide specific columns.
		<br />
		Click
		<a href="#" class="configured_api_verbosity">here</a>
		to
		<span class="show_hide">hide</span>
		logging settings.
		<br />
		<div class="app-log-settings">
			<?php
			if (isset($logSettings) && $logSettings){
				echo("<div class='app-log-info'>Logging is <b>enabled </b>with <b>$defaultLogLevel</b> being the default log level.</div>");
				?>
			<?php
			echo("<div class='app-log-info'><i>API level log settings are displayed below. If no API specific logging level is defined, API would be logged at <b>$defaultLogLevel</b> verbosity.</i> </div>");
			echo("<table border='1'>");
			echo("<tr><th>API Name</th><th>Log Level</th></tr>");
			foreach($apiLogLevelStr as $appLogLevel){
				echo("<tr>");
				echo("<td>$appLogLevel->api</td>");
				echo("<td>$appLogLevel->logLevel</td>");
				echo("</tr>");
			}
			echo("</table>");
			?>
			<?php
			}else{
				echo("Logging is <b>Disabled</b>");
			}
			?>
		</div>
	</div>
	<div class="app_log_info">
		<a href="#" id="refresh_ws_log_page" class='neato-button_alt right' title="Refresh">Refresh</a>
	</div>
	<table class="pretty-table web_service_log display">
		<thead>
			<tr class="notification_datagrid">
				<th style="width: 5%;" class='pretty-table-center-th'>Log ID</th>
				<th style="width: 12%;" class='pretty-table-center-th'>Method</th>
				<th style="width: 10%;" class='pretty-table-center-th'>Robot ID</th>
				<th style="width: 10%;" class='pretty-table-center-th'>User Email</th>
				<th style="width: 20%;" class='pretty-table-center-th text_transform_none'>Request</th>
				<th style="width: 15%;" class='pretty-table-center-th text_transform_none'>Response</th>
				<th style="width: 10%;" class='pretty-table-center-th'>Extra Info</th>
				<th style="width: 7%;" class='pretty-table-center-th'>IP</th>
				<th style="width: 7%;" class='pretty-table-center-th'>Timestamp</th>
				<th style="width: 25%;" class='pretty-table-center-th'>Request Dump</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</fieldset>
<script>

jqNC(document).ready(function(){

	setButtonsStyle();
	defineAppLogDataTable();
	configuredAPIVerbosity();

});

function setButtonsStyle(){
	jqNC(this).find('.neato-button').wrap('<div class="rounded-corners"/>');
}

function configuredAPIVerbosity(){
	jqNC('.configured_api_verbosity').click(function(){
		jqNC('.app-log-settings').toggle(function(){
			jqNC('.show_hide').html("hide");
			if ($('.app-log-settings').css('display') == 'none') {
				jqNC('.show_hide').html("see");
			}
		});
	});
}

function defineAppLogDataTable(){

    var baseURL = '<?php echo $baseURL; ?>';
    var handle = 'web_service_log';
    var length = 100;
    var notification_history_url = '<?php echo $baseURL . '/app/webServiceLog' ?>';
    var colomns_to_disable_sort = [];
    var default_sorting = [ 8, 'desc' ];

    appLogDataTable(handle, length, notification_history_url, colomns_to_disable_sort, default_sorting, 'ws_log_datagrid');
    jqNC('#refresh_ws_log_page').click(function(){
    	appLogDataTable(handle, length, notification_history_url, colomns_to_disable_sort, default_sorting, 'ws_log_datagrid');
    });

}

function appLogDataTable(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call) {
	appLogDataTableObj = jqNC('.' + handle).dataTable({
		"bStateSave" : true,
		"dom": 'C<"clear">lfrtip',
		"aLengthMenu" : [50, 100, 200, 500],
		"fnStateSave" : function(oSettings, oData) {
			sessionStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
		},
		"fnStateLoad" : function(oSettings) {
			return JSON.parse(sessionStorage.getItem('DataTables_' + window.location.pathname));
		},
		"sPaginationType" : "full_numbers",
		"bProcessing" : true,
		"bServerSide" : true,
		"iDisplayLength" : length,
		"aoColumnDefs"     : [
		                      {
		                          "aTargets" : [ 0 ],
		                          "mDataProp" : function ( data, type, val ) {
		                                  // data is processed here
		                                  return data[0];
		                          }
		                      },  // same code for 6 columns
		                      { "bVisible"     : true,          "aTargets" : [1,2,3,4,6,8] },
		                      { "bVisible"     : false,         "aTargets" : [0,5,7,9]},
		                  ],

		"aaSorting" : [ default_sorting ],
		"sAjaxSource" : url,
		"fnServerData" : function(sSource, aoData, fnCallback) {
			jqNC.ajax({
				"dataType" : 'json',
				"type" : "GET",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
				},
				"beforeSend" : function() {
					var loading_show = true;
					aoData.filter(function(val) {
						if (val.name == 'sSearch') {
							if (val.value) {
								loading_show = false;
							}
						}
					});
					if (loading_show) {
						//showWaitDialog();
					}
				}
			});
		},
		"fnRowCallback" : function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			jqNC('td:eq(0), td:eq(1), td:eq(3)', nRow).addClass("pretty-table-center-td");
			jqNC('td:eq(2), td:eq(4), td:eq(5), td:eq(6), td:eq(7), td:eq(8), td:eq(9), td:eq(10)', nRow).addClass("pretty-table-center-td");
		},
		"bDestroy" : true,
	});
}

</script>
