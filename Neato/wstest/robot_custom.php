<?php
$host_name = $_SERVER['HTTP_HOST'];
if ($host_name === '50.116.10.113'){
	header('Location: http://neatodev.rajatogo.com/Neato_Server/Server_Yii/wstest/');
}

$api_key = "1e26686d806d82144a71ea9a99d1b3169adaad917";

switch ($host_name) {
	case "neatostaging.rajatogo.com":
		$baseURL = "http://neatostaging.rajatogo.com/api/rest/json/?method=";//for neato staging;
		break;

	case "neatodev.rajatogo.com":
		$baseURL = "http://neatodev.rajatogo.com/Server_Yii/Neato/api/rest/json?method=";//for neato-yii dev yii;
		break;

	case "localhost":
		$baseURL = "http://localhost/Neato_Server/Server_Yii/Neato/api/rest/json?method=";//for neato-yii localhost
		break;

	default:
		$baseURL = "http://neato.rajatogo.com/api/rest/json/?method=";//for neato production;
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

#addLabelLink,#addLabelLinkUpdate {
	cursor: pointer;
	color: blue;
	width: 310px;
}

.request_div,.response_div {
	max-width: 1200px;
	white-space: pre-wrap; /* CSS3 */
	white-space: -moz-pre-wrap; /* Firefox */
	white-space: -pre-wrap; /* Opera <7 */
	white-space: -o-pre-wrap; /* Opera 7 */
	word-wrap: break-word; /* IE */
}

.expand-all {
	background-color: #F5F5F5;
	color: #000000;
	cursor: pointer;
	float: left;
	font-size: 19px;
	position: fixed;
	width: 100px;
	text-decoration: underline;
}

.row-table {
	width: 70%;
}
</style>

</head>
<body>
	<span class='expand-all'>Expand all</span>
	<br />
	<table class='custom_table'>
		<tr>
			<td colspan="2"><label>Set API key first</label>
			</td>
		</tr>
		<tr>
			<td class='label_field'>api_key</td>
			<td class='value_field'><input type="text" name='api_key'
				id='main_api_key' value='<?php echo($api_key);?>' />
			</td>
		</tr>
		<tr>
			<td><input type='button' class='apply_api_key' id='apply_api_key'
				value='Apply API Key'>
			</td>
			<td></td>
		</tr>
	</table>
	<br />

	<form action="<?php echo($baseURL)?>site.get_api_version" method='POST'
		id='sitegetapiversion' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Get API Version</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to check the API version. <br /> <br /> URL:
						<?php echo($baseURL)?>
						site.get_api_version <br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
						</ul>
						Success Response:
						<ul>
							<li>{"status":0,"result":"1"}</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>
			</tr>

			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='sitegetapiversion'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>

			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
			</tr>
		</table>
	</form>


	<form action="<?php echo($baseURL)?>robot.post_custom_data"
		method='POST' id='robotPostcustomdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Post Robot Custom Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to post robot custom data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.post_custom_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
							<li><b>encoded_blob_data[]</b> :Array of Custom Data of
								key=>value pairs, e.g. encoded_blob_data{'history'=>'encoded
								data', 'recent'=>'encoded data'}.The key is the type and value
								is in base 64 encoded string.You can generate base 64 encoded
								string for a file using this <a href='robot_data_encode.php'
								target='_blank'>link</a>
							</li>
							<li><b>blob_data[]</b> :Array of Custom Data of key=>value pairs,
								e.g. blob_data{'history'=>'robot.jpg', 'recent'=>'room.xml'}</li>
						</ul>
						Scenarios
						<ul>
							<li>If keys and only blob_data[] is provided
								<ul>
									<li>It would create blob file with provided blob_data[] file
										extension.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If keys and only encoded_blob_data[] is provided
								<ul>
									<li>It would create blob file with provided
										encoded_blob_data[].</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If keys, blob_data[] and encoded_blob_data[] are provided
								<ul>
									<li>It would create blob file with provided
										encoded_blob_data[].</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If encoded_blob_data[] is provided
								<ul>
									<li>Blob data check for file mime type,
										<ul>
											<li>if file mime type is image it will check for file
												extension (jpg/jpeg/gif/png)</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>The encoded_blob_data[] and blob_data[] files to be supported
								<ul>
									<li>Only jpg/jpeg/gif/png files are supported by custom data</li>
								</ul>
							</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"robot_custom_id":"2","history":1,"recent":1}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
								</ul>
							</li>
							<li>If serial number provided but keys not provided.
								<ul>
									<li>{"status":-1,"message":"Provide atlest one data"}</li>
								</ul>
							</li>
							<li>If serial number and key provided but both blob_data[] and
								encoded_blob_data[] are not provided.
								<ul>
									<li>{"status":-1,"message":"Provide atlest one data"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.post_custom_data"}</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>

			</tr>
			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>
			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td id='labelPlaceholderRow' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value='' id='labelName'
					class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLink'>Add File Detail Key (considered keys are
						name)</div>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotPostcustomdata'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
			</tr>
		</table>
	</form>

	<form action="<?php echo($baseURL)?>robot.get_customs" method='POST'
		id='robotGetcustoms' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Get Robot Customs</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot customs. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_customs<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":[{"id":"9","history":"1","recent":"1","image":"1"},{"id":"10","img":"1"}]}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and custom does not exist
								<ul>
									<li>{"status":0,"result":[]}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>{"status":-1,"message":""Serial number does not exist""}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.get_customs"}</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>

			</tr>
			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>
			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotGetcustoms'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
			</tr>
		</table>
	</form>

	<form action="<?php echo($baseURL)?>robot.get_custom_data"
		method='POST' id='robotGetcustomdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Get Robot Custom Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot custom data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_custom_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_custom_id</b> :Robot Custom Id</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":[{"recent":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_custom_data\/8\/1354636168_recent.jpg"},{"image":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_custom_data\/8\/1354636168_image.jpg"}]}
									</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If robot custom id does not exist
								<ul>
									<li>{"status":-1,"message":"Robot custom id does not exist"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_custom_id
										in method robot.get_custom_data"}</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>

			</tr>
			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>
			<tr>
				<td>robot_custom_id</td>
				<td><input type="text" name='robot_custom_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotGetcustomdata'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
			</tr>
		</table>
	</form>

	<form action="<?php echo($baseURL)?>robot.update_custom_data"
		method='POST' id='robotUpdatecustomdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Update Robot Custom Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to update robot custom data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.update_custom_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_custom_id</b> :Robot Custom Id</li>
							<li><b>blob_data_version[]</b> :Array of Data Version of
								key=>value pairs,e.g data_version{'history'=>'1', 'recent'=>'1'}</li>
							<li><b>encoded_blob_data[]</b> :(Optional)Array of Custom Data of
								key=>value pairs, e.g. encoded_blob_data{'history'=>'encoded
								data', 'recent'=>'encoded data'}.The key is the type and value
								is in base 64 encoded string.You can generate base 64 encoded
								string for a file using this <a href='robot_data_encode.php'
								target='_blank'>link</a>
							</li>
							<li><b>blob_data[]</b> :(Optional)Array of Custom Data of
								key=>value pairs, e.g. blob_data{'history'=>'robot.jpg',
								'recent'=>'room.xml'}</li>
						</ul>
						Scenarios
						<ul>
							<li>If blob_data_version[] provided and both encoded_blob_data[]
								and blob_data[] are not provided
								<ul>
									<li>It would delete previous blob data file</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data_version[] provided and only blob_data[] is
								provided
								<ul>
									<li>It would delete previous blob data file and create blob
										file with provided blob_data[] file.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data_version[] provided and both encoded_blob_data[]
								and blob_data[] are provided
								<ul>
									<li>It would delete previous blob data file and create blob
										file with provided encoded_blob_data[].</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data_version[] provided and only encoded_blob_data[]
								are provided
								<ul>
									<li>It would delete previous blob data file and create blob
										file with provided encoded_blob_data[].</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If encoded_blob_data[] is provided
								<ul>
									<li>Blob data check for file mime type,
										<ul>
											<li>if file mime type is image it will check for file
												extension (jpg/jpeg/gif/png)</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>The encoded_blob_data[] and blob_data[] files to be supported
								<ul>
									<li>Only jpg/jpeg/gif/png files are supported by custom data</li>
								</ul>
							</li>
						</ul>
						Success Response:
						<ul>
							<li>If blob_data_version[] provided and goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot custom data."}}</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data_version[] and encoded_blob_data[] are provided
								everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot custom data."}}</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data_version[] and blob_data[] are provided
								everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot custom data."}}</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data_version[], encoded_blob_data[] and blob_data[]
								are provided everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot custom data."}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If robot custom id does not exist
								<ul>
									<li>{"status":-1,"message":"Robot custom id does not exist"}</li>
								</ul>
							</li>
							<li>If custom id provided but keys not provided.
								<ul>
									<li>{"status":-1,"message":"Provide atlest one data and
										version."}</li>
								</ul>
							</li>
							<li>If custom id and keys provided but
								blob_data_version[],blob_data[] and encoded_blob_data[] are not
								provided.
								<ul>
									<li>{"status":-1,"message":"Provide atlest one data and
										version."}</li>
								</ul>
							</li>
							<li>If custom id ,keys and blob_data_version[] are provided but
								blob_data_version[] not matching with latest blob_data_version.
								<ul>
									<li>{"status":-1,"message":"Version mismatch for (key_name)"}</li>
								</ul>
							</li>
							<li>If custom id and keys and blob_data_version[] provided but
								keys are not exist
								<ul>
									<li>{"status":-1,"message":"(key_name) not found."}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_custom_id
										in method robot.update_custom_data"}</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>

			</tr>
			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>
			<tr>
				<td>robot_custom_id</td>
				<td><input type="text" name='robot_custom_id'>
				</td>
			</tr>
			<!-- <tr>
				<td>data_version</td>
				<td><input type="text" name='data_version'>
				</td>
			</tr> -->
			<tr>
				<td id='labelPlaceholderRowUpdate' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value=''
					id='labelNameUpdate' class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLinkUpdate'>Add File Detail Key (considered keys
						are name)</div>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotUpdatecustomdata'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
			</tr>
		</table>
	</form>




	<form action="<?php echo($baseURL)?>robot.delete_custom_data"
		method='POST' id='robotDeletecustomdata' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Delete Robot Custom Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete robot custom data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.delete_custom_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_custom_id</b> :Robot Custom Id</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully deleted robot custom data."}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If robot custom id does not exist
								<ul>
									<li>{"status":-1,"message":"Robot custom id does not exist"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_custom_id
										in method robot.delete_custom_data"}</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>

			</tr>
			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>
			<tr>
				<td>robot_custom_id</td>
				<td><input type="text" name='robot_custom_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotDeletecustomdata'
					value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
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
	labelLinkUpdateClick();
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
				$('#labelPlaceholderRow').append("<table class='row-table'><tr><td>" + labelNameVal+"</td><td>encoded_blob_data<br><textarea rows='5' cols='20' name='encoded_blob_data["+labelNameVal+"]'></textarea></td><td>blob_data<br><input type='file' name='blob_data["+labelNameVal+"]'></td></tr><table>");
			}
		}else{
			alert('Key can NOT be empty');
		}
	});
}

function labelLinkUpdateClick(){
	existingLabelNameUpadateArray = new Array();
	$('#addLabelLinkUpdate').click(function(){
		labelNameVal = $('#labelNameUpdate').val();
		if($.trim(labelNameVal)!=''){
			labelExists = false;
			for(i=0; i<existingLabelNameUpadateArray.length; i++){
				existingLabel = existingLabelNameUpadateArray[i];                                                                                                              
				if(existingLabel == labelNameVal){
					labelExists = true;
				}
			}
			if(labelExists){
				alert('Key already added');
			}else{
				existingLabelNameUpadateArray.push(labelNameVal);
				$('#labelPlaceholderRowUpdate').append("<table class='row-table'><tr><td>" + labelNameVal+"</td><td>blob_data_version<br><input type='text' name='blob_data_version["+labelNameVal+"]'></td><td>encoded_blob_data<br><textarea rows='5' cols='20' name='encoded_blob_data["+labelNameVal+"]'></textarea></td><td>blob_data<br><input type='file' name='blob_data["+labelNameVal+"]'></td></tr><table>");
			}
		}else{
			alert('Key can NOT be empty');
		}
	});
}
</script>
</body>
</html>
