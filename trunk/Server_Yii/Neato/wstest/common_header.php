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
		
	case "neatodemo.rajatogo.com":
		$baseURL = "http://neatodemo.rajatogo.com/api/rest/json/?method=";//for neato demo;
		break;

	case "neatodev.rajatogo.com":
		$baseURL = "http://neatodev.rajatogo.com/api/rest/json?method=";//for neato-yii dev yii;
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
<title>User test console</title>
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
	background-color: #F5F5F5;
    color: #000000;
    cursor: pointer;
    float: left;
    font-size: 19px;
    position: fixed;
    width: 100px;
    text-decoration: underline;
    text-align: center;
    right: 0;
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

#addLabelLink, #addLabelLink1, #addLabelLink2, #addLabelLinkUpdate, #loadRegistrationIds, #loadEmails, #addLabelLink3, #addLabelLink4 {
	cursor: pointer;
	color: blue;
	width: 310px;
}

.request_div, .response_div {
	max-width: 1200px;
	white-space: pre-wrap;      /* CSS3 */   
   white-space: -moz-pre-wrap; /* Firefox */    
   white-space: -pre-wrap;     /* Opera <7 */   
   white-space: -o-pre-wrap;   /* Opera 7 */    
   word-wrap: break-word;      /* IE */
}

</style>

</head>
<a class = 'back-to-list' href ="index.php">Back to List</a>
<span class='expand-all'>Expand all</span>
<br />
<body>

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

						Failure Response: <br />
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
