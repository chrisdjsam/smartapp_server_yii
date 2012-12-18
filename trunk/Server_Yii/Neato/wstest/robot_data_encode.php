<?php
//print_r($_REQUEST);
//die;
 
$extension = "";
$encoded_data = "";

if(isset($_REQUEST['submit'])){
// 	$extension = "demo";
// 	$encoded_data = "demo";
	
	if(isset($_FILES['file_data'])){
		$temp_file_path = $_FILES['file_data']['tmp_name'];
		$extension = pathinfo($_FILES['file_data']['name'], PATHINFO_EXTENSION);
		$original_content = file_get_contents($temp_file_path);
		
		//$str = 'This is an encoded string';
		$encoded_data = base64_encode($original_content);
	}
}


$host_name = $_SERVER['HTTP_HOST'];
if ($host_name === '50.116.10.113'){
	header('Location: http://neatodev.rajatogo.com/Neato_Server/Server_Yii/wstest/');
}

$api_key = "1e26686d806d82144a71ea9a99d1b3169adaad917";

switch ($host_name) {
	case "neatostaging.rajatogo.com":
		$baseURL = "http://neatostaging.rajatogo.com/wstest/";//for neato staging;
		break;

	case "neatodev.rajatogo.com":
		$baseURL = "http://neatodev.rajatogo.com/Server_Yii/Neato/wstest/";//for neato-yii dev yii;
		break;

	case "localhost":
		$baseURL = "http://localhost/Neato_Server/Server_Yii/Neato/wstest/";//for neato-yii localhost
		break;

	default:
		$baseURL = "http://neato.rajatogo.com/wstest/";//for neato production;
		break;
}
?>
<html>
<head>
<title>Web Service Test Console</title>
<style type="text/css">
body {
	
}

.custom_table {
	width: 100%;
	border: 1px solid green;
}

.custom_table td.label_field {
	width: 25%;
}

.custom_table td.value_field {
	width: 75%;
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

.expand-all {
	color: blue;
	cursor: pointer;
	width: 100%;
	float: left;
}

.details_div {
	display: none;
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
</style>

</head>

<body>

	<br />
	
	<form action="<?php echo($baseURL)?>robot_data_encode.php" method='POST'
		id='robotpostMap123' class='ajaxified_forms123'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Base 64 encoded data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>
				</td>

			</tr>
			<tr>
				<td>File</td>
				<td><input type="file" name='file_data'>
				</td>
			</tr>
			<tr>
				<td>Base 64 encoded data</td>
				<td><textarea rows="5" cols="40" name='encoded_data'><?php echo $encoded_data?></textarea>
				</td>
			</tr>
	
			<tr>
				<td><input type="Submit" name='submit' dummy='robotpostMap'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
		</table>
	</form>

	<script type="text/javascript" src="jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="jquery.form.js"></script>
	<script type="text/javascript" src="json2.js"></script>
	<script>
$(document).ready(function(){
	$('.toggle_details').text('');
	$('#apply_api_key').click(function(){
		apiKeyVal = $('#main_api_key').val();
		$('.api_keys').val(apiKeyVal);
	});
	$('.submit_form').click(function(){
		formId = $(this).attr('dummy');

		// Avoid sending all the attributes that are not required at the web service end.
		$('.removeFromRequest').each(function(){
			$(this).attr("disabled",true);
		})
		$('#'+formId).submit();
	});
	labelLinkClick();
var options = {
 		beforeSubmit:  showRequest,  // pre-submit callback
        success:       showResponse,  // post-submit callback
        complete:		showComplete
    };
    // bind form using 'ajaxForm'
    $('.ajaxified_forms').ajaxForm(options);

    $('.create_account_type_select').change(function(){
		accountTypeVal = $(this).val();
		$('.create_account_type_dependent').hide();
		$('.'+accountTypeVal).show();
    });

    $('.account_type_select').change(function(){
		accountTypeVal = $(this).val();
		if(accountTypeVal == 'Native'){
			$('.external_social_id_class').hide();
		}else{
			$('.external_social_id_class').show();
		}
    });
    $('.expand-all').click(function(){

    	$('.toggle_details').each(function(){
    		$(this).next().toggle();
    	});	

    	if($(this).text() == 'Expand all'){
    		$(this).text('Collapse all');
    	}else{
    		$(this).text('Expand all');
    	}
    	/*$(this).next().toggle();
    	if($(this).text() == 'More'){
    		$(this).text('Less');
    	}else{
    		$(this).text('More');
    	}*/
    });
});
var formId;
function showComplete(responseText, statusText, xhr, $form){
	try{
		$('#'+formId +  ' .response_div').html("<pre><label>Received Response</label> <br/>Response: " + responseText.responseText + "</pre>");
		$('.removeFromRequest').each(function(){
			$(this).removeAttr("disabled");
		})
	}catch(Exception){
		alert(Exception);
	}
}
// pre-submit callback
function showRequest(formData, jqForm, options) {
	formId = jqForm.attr('id');
	methodType = (jqForm.attr('method'));
	action = (jqForm.attr('action'));
	queryStringData = $.param(formData);
	queryStringArray = queryStringData.split('&');
	queryString = '';
	for(i=0; i<queryStringArray.length;i++){
		queryString = queryString + '<br/>' + decodeURIComponent(queryStringArray[i]);
	}
	$('#'+formId +  ' .request_div').html("<pre><label>Sent Request</label><br/> Action: " + action + '<br/> Method:' + methodType + '<br/> Parameters:' + queryString + "</pre>");
	$('#'+formId +  ' .response_div').html('<label>Waiting for Response....</label>');
    return true;
}
// post-submit callback
function showResponse(responseText, statusText, xhr, $form)  {
	try{
		formId = $form.attr('id');
		$('#'+formId +  ' .response_div').html("<pre><label>Received Response</label> <br/> Status: " + statusText + "<br/>Response: " + JSON.stringify(responseText) + "</pre>");
		$('.removeFromRequest').each(function(){
			$(this).removeAttr("disabled");
		})
	}catch(Exception){
		alert(Exception);
	}
}
function labelLinkClick(){
	existingLabelNameArray = new Array();
	$('#addLabelLink').click(function(){
		labelNameVal = $('#labelName').val();
		if($.trim(labelNameVal)!=''){
			labelExists = false;
			for(i=0; i<existingLabelNameArray.length; i++){
				existingLabel = existingLabelNameArray[i];
				if(existingLabel == labelNameVal){
					labelExists = true;
				}
			}
			if(labelExists){
				alert('Key already added');
			}else{
				existingLabelNameArray.push(labelNameVal);
				$('#labelPlaceholderRow').append("<table style='width:100%'><tr><td>" + labelNameVal+"</td><td><input type='text' name='profile[" + labelNameVal + "]'></tr></td><table>");

			}
		}else{
			alert('Key can NOT be empty');
		}
	});
}
</script>
</body>
</html>
