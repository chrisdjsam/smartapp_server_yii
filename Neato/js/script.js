var genericDataTable;
var check_command_interval;
var check_status_interval;
var end_time;
var end_time_for_robot_avl;
var current_time;
var current_time_for_robot_avl;
var user_role_id;
var aTargets = [];
var bSearchable = [];
var checkRobotAvailabilityInterval;

var command_error_msg = "Robot didn't receive respective command";
var command_success_msg = "Respective command is received by robot";

var robot_status_code = '10001';

function validateEmail(emailVal) {
	if (emailVal == '') {
		return false;
	}
	var emailReg = /^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
	if (!emailReg.test(emailVal)) {
		return false;
	}
	return true;
}

show_all_app_message();

function show_all_app_message() {
	var success_message = "";
	var error_message = "";
	var warning_message = "";

	if ($('.app-messages .flash-success').length > 0) {
		success_message = $('.app-messages .flash-success').html();
	}

	if ($('.app-messages .flash-error').length > 0) {
		error_message = $('.app-messages .flash-error').html();
	}

	if ($('.app-messages .flash-notice').length > 0) {
		warning_message = $('.app-messages .flash-notice').html();
	}

	if (success_message !== "") {
		generate_noty("success", success_message);
	}

	if (error_message !== "") {
		generate_noty("error", error_message);
	}

	if (warning_message !== "") {
		generate_noty("warning", warning_message);
	}
}

function generate_noty(type, text) {
	var n = noty({
		text : text,
		type : type,
		dismissQueue : true,
		layout : 'bottomRight',
		theme : 'default',
		animation : {
			open : {
				height : 'toggle'
			},
			close : {
				height : 'toggle'
			},
			easing : 'swing',
			speed : 500
		// opening & closing animation speed
		},
		timeout : 5000, // delay for closing event. Set false for sticky
						// notifications
		force : false, // adds notification to the beginning of queue when set
						// to true
		modal : false,
		closeWith : [ 'click' ], // ['click', 'button', 'hover']
		callback : {
			onShow : function() {
			},
			afterShow : function() {
			},
			onClose : function() {
			},
			afterClose : function() {
			}
		}
	});
}

$("#WaitingDialog").dialog({
	autoOpen : false,
	closeOnEscape : false,
	title : '',
	resizable : false,
	height : 'auto',
	width : 'auto',
	modal : true,
	open : function(event, ui) {
		jQuery('.ui-dialog-titlebar-close').hide();
		jQuery('.ui-dialog-titlebar').hide();
		jQuery('.ui-widget').removeClass('ui-widget-content');
	},
	close : function(event, ui) {
		jQuery('.ui-dialog-titlebar-close').show();
		jQuery('.ui-dialog-titlebar').show();
		jQuery('.ui-widget').addClass('ui-widget-content');
	}
});

function showWaitDialog() {
	if ($('#WaitingDialog').dialog('isClose')) {
		$('#WaitingDialog').dialog('open');
	}
}

function hideWaitDialog() {
	if ($('#WaitingDialog').dialog('isOpen')) {
		$('#WaitingDialog').dialog('close');
	}
}

$(document).ready(function() {
	createJQtip();
	isRobotAlive();
	clickSearchButton();
	reset_datatable_on_tab_click();

	if (user_role_id == '2') {
		aTargets = [ 0, 1, 2 ];

	} else {
		aTargets = [ 0, 2, 3, 4, 5];
	}
	robotTable = $('.robot-table').dataTable({
		"bStateSave" : true,
		"iDisplayLength" : 25,
		"aoColumnDefs" : [ {
			"bSortable" : false,
			'aTargets' : aTargets
		} ],
		"aaSorting" : [ [ 1, 'asc' ] ],
		"fnFilter" : '',
		"bProcessing" : true,
		"bServerSide" : true,
		"sPaginationType" : "full_numbers",
		"sAjaxSource" : app_base_url + '/api/robot/RobotDataTable',
		"fnServerData" : function(sSource, aoData, fnCallback) {
			$.ajax({
				"dataType" : 'json',
				"type" : "GET",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
					hideWaitDialog();
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
						showWaitDialog();
					}
				}
			});
		},
		"fnRowCallback" : function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$('td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4)', nRow).addClass("pretty-table-center-td");
			$('td:eq(2)', nRow).addClass("multiple-item");

		}
	});

	if (user_role_id == '2') {
		aTargets = [ 0, 1 ];
		bSearchable = [ {
			"bSearchable" : false
		}, null, ];
	} else {
		aTargets = [ 0 ];
		bSearchable = [ null, null, null, null, ];
	}
	
	userTable = $('.user-table').dataTable({
		"bStateSave" : true,
		"iDisplayLength" : 25,
		"aoColumnDefs" : [ {
			"bSortable" : false,
			'aTargets' : aTargets
		} ],
		"fnFilter" : '',
		"aoColumns" : bSearchable,
		"aaSorting" : [ [ 1, 'asc' ] ],
		"bProcessing" : true,
		"bServerSide" : true,
		"sPaginationType" : "full_numbers",
		"sAjaxSource" : app_base_url + '/api/user/UserDataTable',
		"fnServerData" : function(sSource, aoData, fnCallback) {
			$.ajax({
				"dataType" : 'json',
				"type" : "GET",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
					hideWaitDialog();
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
						showWaitDialog();
					}
				}
			});
		},
		"fnRowCallback" : function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$('td:eq(0)', nRow).addClass();
			$('td:eq(3)', nRow).addClass("multiple-item");
		}
	});

	$('.version-table').dataTable({
		"bFilter" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSort" : false
	});

	$('.user-robot-table').dataTable({
		"bStateSave" : true,
		"sPaginationType" : "full_numbers",
		"fnStateSave" : function(oSettings, oData) {
			sessionStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
		},
		"fnStateLoad" : function(oSettings) {
			return JSON.parse(sessionStorage.getItem('DataTables_' + window.location.pathname));
		},
		"iDisplayLength" : 25,
		"aoColumnDefs" : [ {
			"bSortable" : false,
			'aTargets' : [ 0 ]
		} ],
		"aaSorting" : [ [ 1, 'asc' ] ]
	});
	$('.wslogging-table').dataTable({
		"iDisplayLength" : 25,
		"aaSorting" : [ [ 9, 'desc' ] ],
	});

	$('.robot_types-table').dataTable({
		"bStateSave" : true,
		"sPaginationType" : "full_numbers",
		"fnStateSave" : function(oSettings, oData) {
			sessionStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
		},
		"fnStateLoad" : function(oSettings) {
			return JSON.parse(sessionStorage.getItem('DataTables_' + window.location.pathname));
		},
		"iDisplayLength" : 25,
		"aoColumnDefs" : [ {
			"bSortable" : false,
			'aTargets' : [ 0, 5 ]
		} ],
		"aaSorting" : [ [ 1, 'asc' ] ]
	});

	$('.send-start-command').live('click', function() {
		var urlToSendStartCommand = $(this).attr("href");

		var serial_number = $('#view_robot_serial_number').val();

		var time_limit = $('#command_check_time_limit').val();

		end_time = new Date().getTime() + parseInt(time_limit);

		command_error_msg = "Robot didn't receive cleaning command";
		command_success_msg = "Robot starts cleaning";
		robot_status_code = '10002';

		stopCommandStatus();
		commandStatus(serial_number);

		$.ajax({
			type : 'POST',
			url : urlToSendStartCommand,
			dataType : 'jsonp',
			success : function(r) {
				hideWaitDialog();
				if (r.status === 0) {
					generate_noty("success", "You have successfully sent <b>start cleaning</b> command.");
				} else { // Handle errors
					generate_noty("error", "Error while sending start cleaning command.");
				}
			},
			error : function(r) {
				hideWaitDialog();
				generate_noty("error", "Error while sending start cleaning command.");
			},
			beforeSend : function() {
				showWaitDialog();
			},
			complete : function() {
				hideWaitDialog();
			}
		});
		return false;
	});

	$('.send-stop-command').live('click', function() {
		var urlToSendStartCommand = $(this).attr("href");

		var serial_number = $('#view_robot_serial_number').val();

		var time_limit = $('#command_check_time_limit').val();

		end_time = new Date().getTime() + parseInt(time_limit);

		command_error_msg = "Robot didn't receive stop command";
		command_success_msg = "Robot stops cleaning";
		robot_status_code = '10005';

		stopCommandStatus();
		commandStatus(serial_number);

		$.ajax({
			type : 'POST',
			url : urlToSendStartCommand,
			dataType : 'jsonp',
			success : function(r) {
				hideWaitDialog();
				if (r.status === 0) {
					generate_noty("success", "You have successfully sent <b>stop cleaning</b> command.");
				} else { // Handle errors
					generate_noty("error", "Error while sending stop cleaning command.");
				}
			},
			error : function(r) {
				hideWaitDialog();
				generate_noty("error", "Error while sending stop cleaning command.");
			},
			beforeSend : function() {
				showWaitDialog();
			},
			complete : function() {
				hideWaitDialog();
			}
		});
		return false;
	});

	$('.send-to-base-command').live('click', function() {
		var urlToSendToBaseCommand = $(this).attr("href");

		var serial_number = $('#view_robot_serial_number').val();

		var time_limit = $('#command_check_time_limit').val();

		end_time = new Date().getTime() + parseInt(time_limit);

		command_error_msg = "Robot didn't receive 'send to base' command";
		command_success_msg = "Robot received 'send to base' command";
		robot_status_code = '10009';

		stopCommandStatus();
		commandStatus(serial_number);

		$.ajax({
			type : 'POST',
			url : urlToSendToBaseCommand,
			dataType : 'jsonp',
			success : function(r) {
				hideWaitDialog();
				if (r.status === 0) {
					generate_noty("success", "You have successfully sent <b>send to base</b> command.");
				} else { // Handle errors
					generate_noty("error", "Error while sending 'send to base' command.");
				}
			},
			error : function(r) {
				hideWaitDialog();
				generate_noty("error", "Error while sending 'send to base' command.");
			},
			beforeSend : function() {
				showWaitDialog();
			},
			complete : function() {
				hideWaitDialog();
			}
		});
		return false;
	});

	$('.delete-single-robot-schedule').live('click', function() {
		if (confirm("Are you sure you want to delete robot schedule?")) {
			var this_row_handle = $(this);
			var urlToDeleteMap = $(this_row_handle).attr("href");
			$.ajax({
				type : 'POST',
				url : urlToDeleteMap,
				dataType : 'jsonp',
				success : function(r) {
					hideWaitDialog();
					if (r.status === 0) {
						generate_noty("success", "You have successfully deleted a robot schedule.");
						location.reload();
					} else { // Handle errors
						generate_noty("error", "Error while deleting robot schedule.");
					}
				},
				error : function(r) {
					hideWaitDialog();
					generate_noty("error", "Error while deleting robot schedule.");
				},
				beforeSend : function() {
					showWaitDialog();
				},
				complete : function() {
					hideWaitDialog();
				}
			});

		} else {
			return false;
		}
	});

	$('.delete-single-app_version').live('click', function() {
		if (confirm("Are you sure you want to delete app version?")) {
			var this_row_handle = $(this);
			var urlToDeleteApp = $(this_row_handle).attr("href");
			$.ajax({
				type : 'POST',
				url : urlToDeleteApp,
				dataType : 'jsonp',
				success : function(r) {
					hideWaitDialog();
					if (r.status === 0) {
						generate_noty("success", "You have successfully deleted a app version.");
						location.reload();
					} else { // Handle errors
						generate_noty("error", "Error while deleting app version.");
					}
				},
				error : function(r) {
					hideWaitDialog();
					generate_noty("error", "Error while deleting app version.");
				},
				beforeSend : function() {
					showWaitDialog();
				},
				complete : function() {
					hideWaitDialog();
				}
			});

		} else {
			return false;
		}
	});

	hideTableRecords();
	hideDataTablesInfoForSupportUser();
	showTableRecordsOnSearch();
});

function createJQtip() {
	// Make sure to only match links to wikipedia with a rel tag
	$('a.qtipPopuplink').each(
			function() {
				var additionalClass = "";
				if ($(this).hasClass('robot-qtip')) {
					additionalClass = "robot-qtip";
				}
				// We make use of the .each() loop to gain access to each
				// element via the "this" keyword...
				$(this).qtip(
						{
							content : {
								// Set the text to an image HTML string with the
								// correct src URL to the loading image you want
								// to use
								text : '<div class="qtip-wait-dialog"><img class="throbber" src="' + app_base_url
										+ '/images/throbber.gif" alt="Loading..." /></div>',
								ajax : {
									url : $(this).attr('rel')
								// Use the rel attribute of each element for the
								// url to load
								},
								title : {
									text : $(this).attr('title'), // Give the
																	// tooltip a
																	// title
																	// using
																	// each
																	// elements
																	// text
									button : true
								}
							},
							position : {
								at : 'bottom center', // Position the tooltip
														// above the link
								my : 'top center',
								viewport : $(window), // Keep the tooltip
														// on-screen at all
														// times
								effect : true
							// Disable positioning animation
							},
							events : {
								visible : function(event, api) {
								},
								render : function(event, api) {
								},
								show : function(event, api) {
								}
							},
							show : {
								event : 'click',
								solo : true
							// Only show one tooltip at a time
							},
							hide : 'unfocus',
							style : {
								classes : 'qtip-wiki qtip-light qtip-shadow ' + additionalClass
							}
						}) // Make sure it doesn't follow the link when we
							// click it
				.click(function(event) {
					event.preventDefault();
				});

			})
}

function dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call) {

	genericDataTable = $('.' + handle).dataTable({
		"bStateSave" : true,
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
		"aoColumnDefs" : [ {
			"bSortable" : false,
			'aTargets' : colomns_to_disable_sort
		} ],
		"aaSorting" : [ default_sorting ],
		"sAjaxSource" : url,
		"fnServerData" : function(sSource, aoData, fnCallback) {
			$.ajax({
				"dataType" : 'json',
				"type" : "GET",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
					hideWaitDialog();
					if (method_to_call != '') {
					    eval( method_to_call + '(json)' );
					}
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
						showWaitDialog();
					}
				}
			});
		},
		"fnRowCallback" : function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$('td:eq(0), td:eq(1), td:eq(3)', nRow).addClass("pretty-table-center-td");
			if (method_to_call == 'show_me_details') {
				$('td:eq(2), td:eq(4)', nRow).addClass("pretty-table-center-td");
			}

			if (method_to_call == 'ws_log_datagrid') {
				$('td:eq(2), td:eq(4), td:eq(5), td:eq(6), td:eq(7)', nRow).addClass("pretty-table-center-td");
			}
		},
		"bDestroy" : true
		
	});

}

function notification_list_table(handle, length, url, colomns_to_disable_sort, default_sorting) {
	if (genericDataTable) {
		genericDataTable.fnDraw();
	} else {
		dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting);
	}
}

function notification_history_table(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call) {
	if (genericDataTable) {
		genericDataTable.fnDraw();
	} else {
		dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call);
	}
}

function f_timer(serial_number) {

	var urlToCallRepeatdlyAction = app_base_url + '/api/Robot/RobotCurrentStatus';

	current_time = new Date().getTime();
	

	if (current_time > end_time) {
		generate_noty("error", command_error_msg);
		stopCommandStatus();
	}

	$.ajax({
		type : 'POST',
		url : urlToCallRepeatdlyAction,
		dataType : 'jsonp',
		data : {
			serial_number : serial_number,
		},
		success : function(r) {

			if (r.code == robot_status_code) {
				
				updateRobotViewElements(r);
				generate_noty("success", command_success_msg);
				stopCommandStatus();

			}

		}

	});

}

function commandStatus(serial_number) {

	check_command_interval = setInterval(function() {
		f_timer(serial_number);
	}, 4000);

}

function currentRobotStatus(serial_number) {
	hideCommandKey(serial_number);
}

function stopCommandStatus() {
	clearInterval(check_command_interval);
}

function hideCommandKey(serial_number) {

	var urlToCheckRobotAction = app_base_url + '/api/Robot/RobotCurrentStatus';

	$.ajax({
		type : 'POST',
		url : urlToCheckRobotAction,
		dataType : 'jsonp',
		data : {
			serial_number : serial_number
		},
		success : function(data) {
			updateRobotViewElements(data);
			hideWaitDialog();
		},
		beforeSend : function() {
			showWaitDialog();
		}
	});

}

function updateRobotViewElements(data){
	
	if(data.is_online){
		$('#online_status').html(' (ONLINE)');
	}else {
		$('#online_status').html(' (OFFLINE)');
	}
	
	switch (data.code) {
		// case '10001':
		// break;

		case '10002':
			$('.send-start-command_btn').hide();
			$('.send-stop-command_btn').show();
			break;
	
		case '10005':
			$('.send-stop-command_btn').hide();
			$('.send-start-command_btn').show();
			break;
	
		// case '10007':
		// break;
	
		case '10008':
			$('.send-start-command_btn').hide();
			$('.send-stop-command_btn').show();
			break;
	
		// case '10009':
		// break;
	
		// case '10010':
		// break;
	
		default:
			$('.send-stop-command_btn').hide();
			$('.send-start-command_btn').show();
	}
	
}

function hideDataTablesInfoForSupportUser() {
	if (user_role_id == '2') {
		$('.dataTables_info').hide();
		$('.dataTables_length').hide()
		$('.dataTables_paginate').hide();
		$('.dataTables_filter').hide();
	}
}

function hideTableRecords() {
	if (user_role_id == '2') {
		$('.dataTable').children('tbody').hide();
	}
}

function clickSearchButton(){
	$('.search-text-input').keypress(function(e){
		if (e.which == 13) {
			e.preventDefault();
			$('.search-button').trigger('click');
		}
	});
}

function showTableRecordsOnSearch() {
	$('.search-button').unbind('click').click(function() {
		var searchVal = $('.search-text-input').val();
		if (searchVal.length == 0) {
			searchVal = " ";
		}else{
			searchVal = searchVal.trim();
		}

		if ($(this).hasClass('for-user')) {
			userTable.fnFilter(searchVal);
		}
		
		if ($(this).hasClass('for-robot')) {
			robotTable.fnFilter(searchVal);
		}
		
		$('.dataTable').children('tbody').show();
	})
}

function isRobotAlive(){
	$('#is-robot-alive').click(function(){
		var robotSerailNo = $(this).attr('robot-serail-no');
		$.ajax({
			type : 'POST',
			url : app_base_url + '/api/robot/isRobotAlive',
			dataType : 'jsonp',
			data: {
				robotSerailNo : robotSerailNo 
			},
			success : function(r) {
				
				if (r.code === 0) {
					generate_noty("success", "System is checking whether robot is alive or not.");
					var time_limit_for_robot_avail = $('#time_limit_for_check_robot_avail').val();
					current_time_for_robot_avl = new Date().getTime();
					end_time_for_robot_avl = new Date().getTime() + parseInt(time_limit_for_robot_avail);
					checkRobotAvailabilityInterval = setInterval( function(){
						checkRobotAvailability(robotSerailNo);	
					}, 5000 );
					
				} else { // Handle errors
					generate_noty("error", "Contact Administrator");
				}
			},
			error : function(r) {
				hideWaitDialog();
				generate_noty("error", "Error while sending is alive command.");
			},
			beforeSend : function() {
				showWaitDialog();
			},
			complete : function() {
			}
		});
		
	});
}

function checkRobotAvailability(robotSerailNo) {
//	var robotSerailNo = $(this).attr('robot-serail-no');

	current_time_for_robot_avl = new Date().getTime();
	if (current_time_for_robot_avl > end_time_for_robot_avl) {
		generate_noty("error", "Sorry, Robot is dead");
		clearInterval(checkRobotAvailabilityInterval);
		hideWaitDialog();
		return;
	}
	
	$.ajax({
		type : 'POST',
		url : app_base_url + '/api/robot/checkRobotAvailability',
		dataType : 'jsonp',
		data: {
			robotSerailNo : robotSerailNo 
		},
		success : function(r) {
			
			if (r.code == 0) {
				hideWaitDialog();
				generate_noty("success", "Robot is alive");
			    clearInterval(checkRobotAvailabilityInterval);
			}
		},
		error : function(r) {
			hideWaitDialog();
		},
		beforeSend : function() {
			showWaitDialog();
		},	
		complete : function() {
			
		}
	});
}

function jabber_user_history_table(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call) {
	if (genericDataTable) {
		genericDataTable.fnDraw();
	} else {
		dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call);
	}
}

function jabber_robot_history_table(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call) {
	if (genericDataTable) {
		genericDataTable.fnDraw();
	} else {
		dataTableForAll(handle, length, url, colomns_to_disable_sort, default_sorting, method_to_call);
	}
}

function reset_dt_view() {
	sessionStorage.removeItem('DataTables_'+window.location.pathname);
}

function reset_datatable_on_tab_click() {
	$('.menu-user a').click(function(){
		reset_dt_view();
	});
}