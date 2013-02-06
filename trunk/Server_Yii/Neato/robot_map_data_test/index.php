<?php
$host_name = $_SERVER['HTTP_HOST'];


switch ($host_name) {
	case "neatostaging.rajatogo.com":
		$blob_data_url = "http://neatostaging.rajatogo.com/robot_data/9/blob/1353516286.jpg";//for neato-yii localhost
		$xml_data_url = "http://neatostaging.rajatogo.com/robot_data/9/xml/1353516286.xml";
		$map_id = "9";
		break;

	case "neatodev.rajatogo.com":
		$blob_data_url = "http://neatodev.rajatogo.com/Server_Yii/Neato/robot_data/7/blob/1353515729.jpg";//for neato-yii localhost
		$xml_data_url = "http://neatodev.rajatogo.com/Server_Yii/Neato/robot_data/7/xml/1353515729.xml";
		$map_id = "7";
		break;

	case "localhost":
		//$blob_data_url = "http://localhost/Neato_Server/Server_Yii/Neato/robot_data/13/blob/1353487265.jpg";//for neato-yii localhost
		$xml_data_url = "http://localhost/Neato_Server/Server_Yii/Neato/robot_map_data_test/robot-13.xml";
		$blob_data_url = "http://neatostaging.rajatogo.com/robot_data/9/blob/1353516286.jpg";
		$map_id = "13";
		break;

	default:
		$baseURL = "http://neato.rajatogo.com/api/rest/json/?method=";//for neato production;
		break;
}
?>
<html>
<head>
<title>Web Service Test Console</title>
<link rel="stylesheet" type="text/css"	href="../css/jquery-ui/jquery-ui-1.9.0.custom.min.css" />

<style type="text/css">
body {
	
}

.custom_table {
	width: 100%;
	border: 1px solid green;
	margin-top: 10px;
}

.custom_table td.label_field {
	width: 50%;
}

.custom_table td.value_field {
	width: 50%;
}

tr.Facebook {
	
}

.api_description {
	color: black;
	background-color: #F5F5F5;
}

.toggle_details {
	color: blue;
	cursor: pointer;
	width: 100%;
	float: left;
}

.details_div {
	float: right;
	width: 250px;
}

.external_social_id_class {
	display: none;
}

.create_account_type_dependent {
	
}

.Facebook {
	display: none;
}

#addLabelLink {
	cursor: pointer;
	color: blue;
}

.map-data-container {
	height: 600px;
	width: 1000px;
	border-width: 1px;
	background-image: url("<?php echo $blob_data_url;?>");
	background-repeat: no-repeat;
	background-size: 100% 100%;
	float: left;
}

.map-data-container .rectangel-area {
	position: absolute;
	color: white;
	text-align: center;
	display: table;
}

.map-data-container .rectangel-area .area-title {
	vertical-align: middle;
	text-align: center;
	display: inline-block;
    word-wrap: break-word;
}

.map-data-container .rectangel-area .area-edit {
	background-image: url("edit.png");
	background-position: 0 50%;
    background-repeat: no-repeat;
    padding-bottom: 3px;
    padding-left: 20px;
    padding-top: 2px;
    display: none;
}

.map-data-container .rectangel-area .area-delete {
	background-image: url("delete.png");
	background-position: 0 50%;
    background-repeat: no-repeat;
    padding-bottom: 3px;
    padding-left: 20px;
    padding-top: 2px;
    display: none;
}

.no-go {
	background-color: red;
}

.room {
	background-color: green;
}

.map-blob-image {
	
}

.form-container {
	height: 600px;
	width: 250px;
}

#container {
	width: 1000px;
	height: 1000px;
	background: #ddd;
	line-height: 300px;
	text-align: center;
	color: #666;
	-moz-user-select: none;
	-webkit-user-select: none;
	user-select: none;
}

.selection-box {
	position: absolute;
	background: transparent;
	border: 1px dotted #fff;
}

.look-like-a-link {
	text-decoration: underline;
	cursor: pointer;
}
.button-container{
 clear:both;
}
</style>

</head>
<body>
	<div class="map-data-container">
		<!-- <div style="height: 50px; width: 100px; top: 450px; left: 350px"
			class="no-go rectangel-area">
			<span class= "area-title">Kitchen123</span>
			<span class= "area-delete">delete</span>
			<span class= "area-edit">edit</span>
		</div>
		<div style="height: 100px; width: 100px; top: 280px; left: 200px"
			class="room rectangel-area">
			<span class= "area-title">Bed room123</span>
			<span class= "area-delete">delete</span>
			<span class= "area-edit">edit</span>
		</div> -->
	</div>
	<div class='details_div'>
	<b>Instructions </b>
		<ul>
			<li>Default mode is "edit mode"</li>
			<li>To enable drawing mode click on "Enable drawing mode" button</li>
			<li>To edit/delete added area click on "Enable edit mode" button</li>
			<li>Draw area by holding the left click over the map area.</li>
		</ul>
	</div>
	<div class='button-container'>
		<input type="button" name='active-drawing-mode' value='Enable drawing mode' class='active-drawing-mode'>
		<input type="button" name='active-edit-mode' value='Enable edit mode' class='active-edit-mode'>
	</div>
	<div class='form-container' style='display:none' title='Add/Edit new area for ROBOT MAP ID:<?php echo($map_id);?>'>
		<form action="" method='POST' id='create-area' class='ajaxified_forms'>

			<table class='custom_table'>
				
				<tr>
					<td class='label_field'>bottom-left-coordinate (X)</td>
					<td class='value_field'><input type="text"
						name='bottom-left-coordinate-x' class='bottom-left-coordinate-x required'
						value='100' /></td>
				</tr>
				<tr>
					<td class='label_field'>bottom-left-coordinate (Y)</td>
					<td class='value_field'><input type="text"
						name='bottom-left-coordinate-y' class='bottom-left-coordinate-y required'
						value='200' /></td>
				</tr>

				<tr>
					<td class='label_field'>top-left-coordinate (X)</td>
					<td class='value_field'><input type="text"
						name='top-left-coordinate-x' class='top-left-coordinate-x required'
						value='50' />
					</td>
				</tr>
				<tr>
					<td class='label_field'>top-left-coordinate (Y)</td>
					<td class='value_field'><input type="text"
						name='top-left-coordinate-y' class='top-left-coordinate-y required'
						value='200' />
					</td>
				</tr>

				<tr>
					<td class='label_field'>top-right-coordinate (X)</td>
					<td class='value_field'><input type="text"
						name='top-right-coordinate-x' class='top-right-coordinate-x required'
						value='50' /></td>
				</tr>
				<tr>
					<td class='label_field'>top-right-coordinate (Y)</td>
					<td class='value_field'><input type="text"
						name='top-right-coordinate-y' class='top-right-coordinate-y required'
						value='300' /></td>
				</tr>

				<tr>
					<td class='label_field'>bottom-right-coordinate (X)</td>
					<td class='value_field'><input type="text"
						name='bottom-right-coordinate-x' class='bottom-right-coordinate-x required'
						value='100' /></td>
				</tr>
				<tr>
					<td class='label_field'>bottom-right-coordinate (Y)</td>
					<td class='value_field'><input type="text"
						name='bottom-right-coordinate-y' class='bottom-right-coordinate-y required'
						value='300' /></td>
				</tr>

				<tr>
					<td>name</td>
					<td><input type="text" name='area-name' class='area-name required required'
						value='Play area'></td>
				</tr>

				<tr>
					<td>area_type</td>
					<td><select name='area_type' class='area_type required'>
							<option value="no-go" selected="selected">No go</option>
							<option value="room">Room</option>
					</select></td>

				</tr>
			</table>
		</form>
	</div>

	<!-- Mouse position
	<h2 id="status">0, 0</h2> -->

	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../js/libs/jquery-ui-1.8.16.min.js"></script>
	<script type="text/javascript" src="jquery.form.js"></script>
	<script type="text/javascript" src="../js/libs/jquery.validate.js"></script>
	<script type="text/javascript" src="json2.js"></script>
	<script>
	var current_mode = '';
	var is_edit_mode = false;
	var edit_area_handle;
$(document).ready(function(){

	$("#create-area").validate();
	
 	/*$(document).mousemove(function(e){
      $('#status').html(e.pageX +', '+ e.pageY);
   	}); */
   
	var xml_file_url = "<?php echo $xml_data_url;?>";

	$.get(xml_file_url, function(data) {
	  show_map_data(data)
	});

	$('.submit_form').click(function(){
		//formId = $(this).attr('dummy');
		//alert($(".bottom-left-coordinate-x").val());
	});
	
	$('.toggle_details').click(function(){
    	$(this).next().toggle();
    	if($(this).text() == 'More'){
    		$(this).text('Less');
    	}else{
    		$(this).text('More');
    	}
    });
    
    //bind_draw_rect();
	bind_edit_click();
	bind_delete_click();
	
	bind_active_drawing_mode_click();
	bind_active_edit_mode_click();
	//set_default_mode();
});

function set_default_mode(){
	call_after_active_edit_mode_click($('.active-edit-mode'));
}

function bind_active_drawing_mode_click(){
	$('.active-drawing-mode').unbind('click').click(function(){
		$('.active-edit-mode').removeAttr('disabled');
    	$(this).attr('disabled','disabled');
    	bind_draw_rect();
    	$('.rectangel-area .area-delete').hide();
    	$('.rectangel-area .area-edit').hide();
    	current_mode = 'draw';
    });
}

function bind_active_edit_mode_click(){
	$('.active-edit-mode').unbind('click').click(function(){
    	call_after_active_edit_mode_click(this);
    });
}

function call_after_active_edit_mode_click($btn_handle){
	//alert($($btn_handle).html());
	$('.active-drawing-mode').removeAttr('disabled');
	$($btn_handle).attr('disabled','disabled');
    unbind_draw_rect();
    $('.rectangel-area .area-delete').show();
    $('.rectangel-area .area-edit').show();
    current_mode = 'edit';
}

function bind_delete_click(){
	$('.rectangel-area .area-delete').unbind('click').click(function(){
    	$(this).parent().remove();
    });
}

function bind_edit_click(){
	$('.rectangel-area .area-edit').unbind('click').click(function(){
		$selection = $(this).parent();
		var a_height = $selection.height();
        var a_width = $selection.width();
        var position = $selection.position();
        var a_top = position.top;
        var a_left = position.left;
        var type = "room";
        var title = "";
        
        title = $selection.children(".area-title").html();
        
        if($selection.hasClass("no-go")){
        	type = "no-go";
        }
	        
        show_rectangle_coordinates(a_height, a_width, a_top, a_left, type, title);
        
        is_edit_mode = true;
        edit_area_handle = $(this).parent();
        //alert("is_edit_mode3 val::" + is_edit_mode);
        
    	//$(this).parent().remove();
    });
}

function show_map_data(data){
	// alert(data);
	 //console.log(data);
	  $(data).find('areas').each(function(){
		  $(this).find('area').each(function(){
			  var title = $(this).find('title').text();
			  title = $.trim(title);
			  var type = $(this).find('type').text();
			  type = $.trim(type);
			  //logic to get top, left, height, width value
			   var a_height = '';
			   var a_width = '';
			   var a_top = '';
			   var a_left = '';
			  $(this).find('coordinates').each(function(){
				  var coordinate_count = 1;
				  var x1_val = '';
				  var x2_val = '';

				  var y2_val = '';
				  var y3_val = '';
				  
				  $(this).find('coordinate').each(function(){
					  if(coordinate_count == 1){
						  x1_val = $.trim($(this).find('x').text());
					  }
					 if(coordinate_count == 2){
						 x2_val = $.trim($(this).find('x').text());
						 y2_val = $.trim($(this).find('y').text());
						 a_height = x1_val - x2_val;
						 a_top = x2_val;
						 a_left = y2_val;
					  }
					 if(coordinate_count == 3){
						 y3_val = $.trim($(this).find('y').text());
						 a_width = y3_val - y2_val;
					  } 
					  coordinate_count ++;
				  });
			  });  
			  //alert(a_width);
			 show_indivisual_data(a_height, a_width, a_top, a_left, type, title) ;
		  });
	  });
	  set_default_mode();
}

function show_indivisual_data(a_height, a_width, a_top, a_left, type, title){
	var cssObj = {
      'height' : a_height + 'px',
      'width' : a_width + 'px',
      'top' : a_top + 'px',
      'left' : a_left + 'px'
    };
		 
	var div_handle = $("<div>").
					addClass(type).
					addClass("rectangel-area").
		  			attr({
							title: title
					}).
					css(cssObj).
		  			html("<span class= 'area-delete look-like-a-link'></span>" + "<span class= 'area-edit look-like-a-link'></span>" + "<br /><span class= 'area-title' style='width:"+a_width+"px'>"+ title +"</span>").
		  			appendTo(".map-data-container");

	bind_edit_click();
	bind_delete_click();
	update_to_current_mode();
}

function update_to_current_mode(){
	if(current_mode === 'edit'){
		$('.rectangel-area .area-delete').show();
    	$('.rectangel-area .area-edit').show();
	}
}

function unbind_draw_rect(){
	var $container = $('.map-data-container');
	$container.unbind('mousedown').unbind('mousemove').unbind('mouseup');
}

function bind_draw_rect(){
	var $container = $('.map-data-container');
    var $selection = $('<div>').addClass('selection-box');

    $container.unbind('mousedown').on('mousedown', function(e) {
        var click_y = e.pageY;
        var click_x = e.pageX;

        $selection.css({
          'top':    click_y,
          'left':   click_x,
          'width':  0,
          'height': 0
        });
        $selection.appendTo($container);

        $container.unbind('mousemove').on('mousemove', function(e) {
            var move_x = e.pageX,
                move_y = e.pageY,
                width  = Math.abs(move_x - click_x),
                height = Math.abs(move_y - click_y);

            $selection.css({
                'width':  width,
                'height': height,
                'word-break': 'break-all',
            });
            if (move_x < click_x) { //mouse moving left instead of right
                $selection.css({
                    'left': click_x - width
                });
            }
            if (move_y < click_y) { //mouse moving up instead of down
                $selection.css({
                    'top': click_y - height
                });
            }
        }).unbind('mouseup').on('mouseup', function(e) {
            $container.off('mousemove');

            var a_height = $selection.height();
            var a_width = $selection.width();
            var position = $selection.position();
            var a_top = position.top;
            var a_left = position.left;
            var type = "room";
            var title = "";
            /*console.log("height::" + $selection.height());
            console.log("width::" + $selection.width());
            console.log("top::" + position.top);
            console.log("left::" + position.left);*/
            if(a_height !== 0 && a_width !== 0){
            	show_rectangle_coordinates(a_height, a_width, a_top, a_left, type, title);
            }
            //$selection.remove();
        });
    });
}

function show_rectangle_coordinates(a_height, a_width, a_top, a_left, type, title){
	var x1_val = Math.abs(a_top + a_height);
	var x2_val = a_top;
	var x3_val = a_top;
	var x4_val = Math.abs(a_top + a_height); 

	var y1_val = a_left;
	var y2_val = a_left;
	var y3_val = Math.abs(a_left + a_width); 
	var y4_val = Math.abs(a_left + a_width);

	$(".bottom-left-coordinate-x").val(x1_val);
	$(".top-left-coordinate-x").val(x2_val);
	$(".top-right-coordinate-x").val(x3_val);
	$(".bottom-right-coordinate-x").val(x4_val);

	$(".bottom-left-coordinate-y").val(y1_val);
	$(".top-left-coordinate-y").val(y2_val);
	$(".top-right-coordinate-y").val(y3_val);
	$(".bottom-right-coordinate-y").val(y4_val);
	
	$(".area-name").val(title);
	$('.area_type').val(type);
	
	show_add_or_edit_dialog();
}

function show_add_or_edit_dialog(){
$(".form-container").dialog({
			resizable : false,
			height : 560,
			width : 470,
			modal : true,
			open : function(event, ui) {
				jQuery('.ui-dialog-title').css('color', '#CD0A0A');
			},
			close: function( event, ui ) {
				$('.selection-box').remove();
				is_edit_mode = false;
				//alert("is_edit_mode1 val::" + is_edit_mode);
			},
			buttons : {
				"Submit" : function() {
					if ($("#create-area").valid()) {
						add_rectangle();
						//alert("is_edit_mode2 val::" + is_edit_mode);
						if(is_edit_mode){
							is_edit_mode = false;
							edit_area_handle.remove();
						}
						
						$(this).dialog("close");
						return true;
					}
				},
				"Cancel" : function() {
					$(this).dialog("close");
				}
			}
		});
}

function add_rectangle(){
	var x1_val = $.trim($(".bottom-left-coordinate-x").val());
	var x2_val = $.trim($(".top-left-coordinate-x").val());
	var y2_val = $.trim($(".top-left-coordinate-y").val());
	var y3_val = $.trim($(".top-right-coordinate-y").val());
	  
	var a_height = x1_val - x2_val;
	var a_width = y3_val - y2_val;
	var a_top = x2_val;
	var a_left = y2_val;
	var type = $.trim($(".area_type").val());
	var title = $.trim($(".area-name").val());
	show_indivisual_data(a_height, a_width, a_top, a_left, type, title) ;
	$('.selection-box').remove();
}

</script>
</body>
</html>
