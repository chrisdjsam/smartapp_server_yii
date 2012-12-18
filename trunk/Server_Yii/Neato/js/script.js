$('.btn-facebook').click(function() {
    //$(".login-element").hide();
    //$(".loading-bar").show();
    FB.login(function(response) {
        if (response.status == 'connected') {
            $.ajax({
                type: 'POST',
                url: app_base_url +'/api/user/fblogin',
                dataType: 'jsonp',
                data: {
                    r: response
                },
                success: function(r) {
                	hideWaitDialog();
                    if (r.status === 0) {
                    	generate_noty("success", r.message);
                        window.location = location.protocol+'//'+window.location.hostname+redirect_url;
                    } else { // Handle errors
                        generate_noty("error", "Error on Facebook Login");
                    }
                },
                error: function(r) {
                	hideWaitDialog();
                    generate_noty("error", "Error on Facebook Login");
                },
                beforeSend: function(){
                	showWaitDialog();
                },
                complete: function(){
                	hideWaitDialog();
                }
            });
        }else{
            $(".login-element").show();
            $(".loading-bar").hide();
            generate_noty("error", "Error on Facebook Login");
        }
    }, {
        scope: fb_permissions
    });
});

function validateEmail(emailVal){
    if(emailVal == ''){
        return false;
    }
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if(!emailReg.test(emailVal)){
        return false;
    } 
    return true;
}

show_all_app_message();

function show_all_app_message(){
	var success_message = "";
	var error_message = "";
	var warning_message = "";
	
	if ($('.app-messages .flash-success').length > 0){
		success_message = $('.app-messages .flash-success').html();
	}
	
	if ($('.app-messages .flash-error').length > 0){
		error_message = $('.app-messages .flash-error').html();
	}
	
	if ($('.app-messages .flash-notice').length > 0){
		warning_message = $('.app-messages .flash-notice').html();
	}
	
	if(success_message !== ""){
		generate_noty("success", success_message);
	}
	
	if(error_message !== ""){
		generate_noty("error", error_message);
	}
	
	if(warning_message !== ""){
		generate_noty("warning", warning_message);
	}
}

function generate_noty(type, text) {
    var n = noty({
        text: text,
        type: type,
        dismissQueue: true,
        layout: 'bottomRight',
        theme: 'default',
        animation: {
            open: {
                height: 'toggle'
            },
            close: {
                height: 'toggle'
            },
            easing: 'swing',
            speed: 500 // opening & closing animation speed
        },
        timeout: 5000, // delay for closing event. Set false for sticky notifications
        force: false, // adds notification to the beginning of queue when set to true
        modal: false,
        closeWith: ['click'], // ['click', 'button', 'hover']
        callback: {
            onShow: function() {},
            afterShow: function() {},
            onClose: function() {},
            afterClose: function() {}
        }
    });
}

$("#WaitingDialog").dialog({
	   autoOpen: false,
	   closeOnEscape: false,
	   title: '',
	   resizable: false,
	   height: 'auto',
	   width: 'auto',
	   modal: true,
	   open: function(event, ui) {
	   jQuery('.ui-dialog-titlebar-close').hide();
	   jQuery('.ui-dialog-titlebar').hide();
	   jQuery('.ui-widget').removeClass('ui-widget-content');
	   }
	});

	function showWaitDialog() {
	       if ($('#WaitingDialog').dialog('isClose')){
	               $('#WaitingDialog').dialog('open');
	       }
	}
	
	function hideWaitDialog() {
	   if ($('#WaitingDialog').dialog('isOpen')){
	       $('#WaitingDialog').dialog('close');
	   }
	}
$(document).ready(function(){
	createJQtip();
	$('.robot-table').dataTable(
			{"iDisplayLength": 25,
				"aoColumnDefs": [{"bSortable":false, 'aTargets': [0, 2, 5]}],
				"aaSorting": [ [1,'asc']]}		
	);

	$('.user-table').dataTable(
			{"iDisplayLength": 25,
				"aoColumnDefs": [{"bSortable":false, 'aTargets': [0, 3]}],
				"aaSorting": [ [1,'asc']]}		
	);
	$('.user-robot-table').dataTable(
			{"iDisplayLength": 25,
				"aoColumnDefs": [{"bSortable":false, 'aTargets': [0]}],
				"aaSorting": [ [1,'asc']]}		
	);
	$('.wslogging-table').dataTable(
			{"iDisplayLength": 25,
				"aaSorting": [ [9,'desc']]}		
	);
	
	var robot_map_table = $('.robot-map-table').dataTable(
			{"iDisplayLength": 10,
				"aoColumnDefs": [{"bSortable":false, 'aTargets': [5]}],
				"aaSorting": [ [0,'desc']]}		
	);
	
	$('.delete-single-robot-map').live('click', function(){
		if(confirm("Are you sure you want to delete robot map?")){
			var urlToDeleteMap = $(this).attr("href");
			var this_row_handle = $(this);
			$.ajax({
                type: 'POST',
                url: urlToDeleteMap,
                dataType: 'jsonp',
                success: function(r) {
                	hideWaitDialog();
                    if (r.status === 0) {
                    	var row = $(this_row_handle).closest("tr").get(0);
            	    	robot_map_table.fnDeleteRow(robot_map_table.fnGetPosition(row));
            	    	generate_noty("success", "You have successfully deleted a robot map.");
                    } else { // Handle errors
                        generate_noty("error", "Error while deleting robot map.");
                    }
                },
                error: function(r) {
                	hideWaitDialog();
                	 generate_noty("error", "Error while deleting robot map.");
                },
                beforeSend: function(){
                	showWaitDialog();
                },
                complete: function(){
                	hideWaitDialog();
                }
            });
	    }else{
	        return false;
	    }
	});
	
	var robot_schedule_table = $('.robot-schedule-table').dataTable(
			{"iDisplayLength": 10,
				"aoColumnDefs": [{"bSortable":false, 'aTargets': [6]}],
				"aaSorting": [ [0,'desc']]}		
	);
	
	$('.delete-single-robot-schedule').live('click', function(){
		if(confirm("Are you sure you want to delete robot schedule?")){
			var this_row_handle = $(this);
			var urlToDeleteMap = $(this_row_handle).attr("href");
			$.ajax({
                type: 'POST',
                url: urlToDeleteMap,
                dataType: 'jsonp',
                success: function(r) {
                	hideWaitDialog();
                    if (r.status === 0) {
                    	var row = $(this_row_handle).closest("tr").get(0);
            	    	robot_schedule_table.fnDeleteRow(robot_schedule_table.fnGetPosition(row));
            	    	generate_noty("success", "You have successfully deleted a robot schedule.");
                    } else { // Handle errors
                        generate_noty("error", "Error while deleting robot schedule.");
                    }
                },
                error: function(r) {
                	hideWaitDialog();
                	 generate_noty("error", "Error while deleting robot schedule.");
                },
                beforeSend: function(){
                	showWaitDialog();
                },
                complete: function(){
                	hideWaitDialog();
                }
            });
			
	    }else{
	        return false;
	    }
	});
	
})


function createJQtip(){
	// Make sure to only match links to wikipedia with a rel tag
	$('a.qtipPopuplink').each(function()
	{
		var additionalClass = "";
		if($(this).hasClass('robot-qtip')){
			additionalClass = "robot-qtip";
		}
		// We make use of the .each() loop to gain access to each element via the "this" keyword...
		$(this).qtip(
		{
			content: {
				// Set the text to an image HTML string with the correct src URL to the loading image you want to use
				text: '<div class="qtip-wait-dialog"><img class="throbber" src="' + app_base_url+'/images/throbber.gif" alt="Loading..." /></div>',
				ajax: {
						url: $(this).attr('rel') // Use the rel attribute of each element for the url to load
				},
				title: {
					text: $(this).attr('title'), // Give the tooltip a title using each elements text
					button: true
				}
			},
			position: {
				at: 'bottom center', // Position the tooltip above the link
				my: 'top center',
				viewport: $(window), // Keep the tooltip on-screen at all times
				effect: true, // Disable positioning animation
			},
			events: {
				visible: function(event, api) {
				},
				render: function(event, api) {
				},
				show: function(event, api) {
				},
			},
			show: {
				event: 'click',
				solo: true // Only show one tooltip at a time
			},
			hide: 'unfocus',
			style: {
				classes: 'qtip-wiki qtip-light qtip-shadow ' + additionalClass
			}
		})	// Make sure it doesn't follow the link when we click it
		.click(function(event) { event.preventDefault(); });

	})
}
