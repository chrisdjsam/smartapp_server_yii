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

	<form action="<?php echo($baseURL)?>user.create" method='POST'
		id='usercreate' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Create User</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to create the users with or without social networking
						information. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.create<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>name</b> :Name of the user</li>
							<li><b>email</b> :Email of the user</li>
							<li><b>password</b> :Password of the user. It does not need to be
								unique.</li>
							<li><b>account_type</b> :Native OR Facebook (OR Google etc)</li>
							<li><b>external_social_id</b> :External Social ID (e.g. Facebook
								ID (numeric value) that is returned by the Facebook). This is
								required ONLY when the account type is NOT Native.</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"success":true,"guid":1074,"user_handle":"d8828e4ef9596dd0be3b8c4cf0de9502"}}
									</li>
								</ul>
							</li>
							<li>If email does exist but the social information does not exist
								<ul>
									<li>{"status":0,"result":{"success":true,"guid":55,"message":"Merged
										user","user_handle":"ce475c5c9b84938f368efe99100b2a11"}}</li>
								</ul>
							</li>

						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If unsupported account type is passed
								<ul>
									<li>{"status":-1,"message":"Account Type is NOT supported."}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter name in method
										user.create"}</li>
								</ul>
							
							<li>If Email does not valid
								<ul>
									<li>{"status":-1,"message":"The email address you provided does
										not appear to be a valid email address."}</li>
								</ul>
							
							<li>If email already exists and account type is native
								<ul>
									<li>{"status":-1,"message":"This email address has already been
										registered."}</li>
								</ul>
							</li>
							<li>If Social information exists and the account type is Facebook
								<ul>
									<li>{"status":-1,"message":"This social information already
										exists."}</li>
								</ul>
							</li>
							<li>If Jabber service does not able to create chat user
								<ul>
									<li>{"status":-1,"message":"User could not be created because
										jabber service in not responding."}</li>
								</ul>
							</li>

						</ul>
					</div>
				</td>

			</tr>
			<tr>
				<td class='label_field'>api_key</td>
				<td class='value_field'><input type="text" name='api_key'
					class='api_keys' value='<?php echo($api_key);?>' /></td>
			</tr>
			<tr>
				<td>name</td>
				<td><input type="text" name='name'></td>
			</tr>

			<tr>
				<td>email</td>
				<td><input type="text" name='email'></td>
			</tr>
			<tr>
				<td>password</td>
				<td><input type="text" name='password'></td>
			</tr>
			<tr>
				<td>account_type</td>
				<td><select name='account_type' class='account_type_select'>
						<option value="Native" selected="selected">Native</option>
						<option value="Facebook">Facebook</option>
				</select></td>

			</tr>
			<tr class='external_social_id_class'>
				<td>external_social_id</td>
				<td><input type="text" name='external_social_id'></td>
			</tr>
			<tr class='external_social_id_class'>
				<td>social_additional_attributes['auth_token']</td>
				<td><input type="text"
					name="social_additional_attributes[auth_token]"></td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='usercreate'
					value='Submit' class='submit_form'></td>
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

	<form action="<?php echo($baseURL)?>auth.get_user_auth_token"
		method='POST' id='authgetuserauthtoken' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Get User Auth Token</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get the user auth token for a user. You can either
						select Native and pass on both email and password or select
						Facebook and pass on social external id. <br /> <br /> URL:
						<?php echo($baseURL)?>
						auth.get_user_auth_token<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>account_type</b> :Account type</li>
							<li>If account type is native
								<ul>
									<li><b>email</b> :User's Email</li>
									<li><b>password</b> :User's Password</li>
								</ul>
							</li>
							<li>If account type is Facebook
								<ul>
									<li><b>external_social_id</b> :User's External Social ID</li>
								</ul>
							</li>
						</ul>
						Success Response:
						<ul>
							<li>{"status":0,"result":"d769de7939af3e76a54ac4a4368e88af"}</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>

							<li>If incorrect email/password combination and account type is
								Native
								<ul>
									<li>{"status":-1,"message":"User could not be authenticated"}</li>
								</ul>
							</li>
							<li>If incorrect external social ID and account type is Facebook
								<ul>
									<li>{"status":-1,"message":"User could not be authenticated"}</li>
								</ul>
							</li>
							<li>If unsupported account type is passed (e.g. Google, for the
								time being)
								<ul>
									<li>{"status":-1,"message":"Account Type is not supported"}</li>
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
				<td>account_type</td>
				<td><select name='account_type' class='create_account_type_select'>
						<option value="Native" selected="selected">Native</option>
						<option value="Facebook">Facebook</option>
				</select>
				</td>
			</tr>
			<tr class="create_account_type_dependent Facebook">
				<td>external_social_id</td>
				<td><input type="text" name='external_social_id'>
				</td>
			</tr>
			<tr class="create_account_type_dependent Native">
				<td>email</td>
				<td><input type="text" name='email'>
				</td>
			</tr>
			<tr class="create_account_type_dependent Native">
				<td>password</td>
				<td><input type="text" name='password'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='authgetuserauthtoken'
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

	<form action="<?php echo($baseURL)?>user.set_account_details"
		method='POST' id='usersetaccountdetails' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Set User Account Details</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set user's account details. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.set_account_details<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User's email (Optional)</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
							<li><b>profile</b> :Map of key=>value pairs, e.g.
								profile{'name'=>'james bond',
								'facebook_external_social_id'='12312111'}</li>
						</ul>
						Success Response:
						<ul>
							<li>{"status":0,"result":"1"}</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If Auth token Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the Auth token
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
					class='api_keys' value='<?php echo($api_key);?>' /></td>
			</tr>
			<tr>
				<td class='label_field'>email</td>
				<td class='value_field'><input type="text" name='email' /></td>
			</tr>

			<tr>
				<td>auth_token</td>
				<td><input type="text" name='auth_token'></td>
			</tr>
			<tr>
				<td id='labelPlaceholderRow' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value='' id='labelName'
					class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLink'>Add Account Detail Key (considered keys are
						name, facebook_external_social_id)</div>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit' dummy='usersetaccountdetails'
					value='Submit' class='submit_form'></td>
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

	<form action="<?php echo($baseURL)?>user.get_user_account_details"
		method='POST' id='usergetuseraccountdetails' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td colspan="2"><label>Get User Account Details</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get the User's Details. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.get_user_account_details<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User's email (Optional)</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine and social information does not exist
								<ul>
									<li>
										{"status":0,"result":{"id":367,"name":"pradip37","email":"pradip_patro378@gmail.com","chat_id":"1350922773_user@rajatogo","chat_pwd":"1350922773_user","social_networks":[]}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and social information exists
								<ul>
									<li>
										{"status":0,"result":{"id":357,"name":"pradip3","email":"pradip_patro3@gmail.com","chat_id":"1350911036_user@rajatogo","chat_pwd":"1350911036_user","social_networks":[{"provider":"Facebook"},{"external_social_id":"123456789"}]}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine,social information does not exist and robot association exists
								<ul>
									<li>
										{"status":0,"result":{"id":542,"name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo","chat_pwd":"1351499916_user","social_networks":[],"robots":[{"id":"68","name":"room
										cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo"},{"id":"69","name":"desk
										cleaner60","serial_number":"robo6","chat_id":"1350991375_robot@rajatogo"}]}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and both social information and robot association do not exist
								<ul>
									<li>
										{"status":0,"result":{"id":543,"name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo","chat_pwd":"1351500158_user","social_networks":[],"robots":[]}}
									</li>
								</ul>
							</li>

						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If Auth token Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the Auth token
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
				<td class='label_field'>email</td>
				<td class='value_field'><input type="text" name='email' />
				</td>
			</tr>
			<tr>
				<td>auth_token</td>
				<td><input type="text" name='auth_token'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit'
					dummy='usergetuseraccountdetails' value='Submit'
					class='submit_form'>
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


	<form action="<?php echo($baseURL)?>user.get_associated_robots"
		method='POST' id='userassociatedrobots' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td colspan="2"><label>Get User associated robots</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get the User associated robots Details. <br /> <br />
						URL:
						<?php echo($baseURL)?>
						user.get_associated_robots<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User's email (Optional)</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine and robot association exists
								<ul>
									<li>{"status":0,"result":[{"id":"68","name":"room
										cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo"},{"id":"69","name":"desk
										cleaner60","serial_number":"robo6","chat_id":"1350991375_robot@rajatogo"}]}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and robot association does not exist
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
							<li>If Auth token Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the Auth token
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
				<td class='label_field'>email</td>
				<td class='value_field'><input type="text" name='email' />
				</td>
			</tr>
			<tr>
				<td>auth_token</td>
				<td><input type="text" name='auth_token'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='userassociatedrobots'
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

	<form action="<?php echo($baseURL)?>user.update_auth_token_expiry"
		method='POST' id='userUpdateauthtokenexpiry' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td colspan="2"><label>Update User Auth Token Expiry</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Update the User Auth token expiry. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.update_auth_token_expiry<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You are
										successfully updated auth token expiry date."}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If Auth token is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the User
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
				<td>auth_token</td>
				<td><input type="text" name='auth_token'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit'
					dummy='userUpdateauthtokenexpiry' value='Submit'
					class='submit_form'>
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


	<form action="<?php echo($baseURL)?>user.logout_auth_token"
		method='POST' id='userlogoutauthtoken' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td colspan="2"><label>Logout User Auth Token</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Delete the User Auth token. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.logout_auth_token<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User's email (Optional)</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You are
										successfully logged out."}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If Auth token against provided email does not exist:
								<ul>
									<li>{"status":-1,"message":"User could not be authenticated"}</li>
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
				<td class='label_field'>email</td>
				<td class='value_field'><input type="text" name='email' />
				</td>
			</tr>
			<tr>
				<td>auth_token</td>
				<td><input type="text" name='auth_token'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='userlogoutauthtoken'
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

	<form action="<?php echo($baseURL)?>user.disassociate_robot"
		method='POST' id='disassociateuserfromrobot' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td colspan="2"><label>Disassociate User from Robot or Robots</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Disassociate User from Robot or Robots. <br /> <br />
						URL:
						<?php echo($baseURL)?>
						user.disassociate_robot<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User's email</li>
							<li><b>serial_number</b> :Serial Number of robot (If this field
								is empty, it would delete all robot association for this
								particular user)</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine, robot serial number provided and
								user robot association exist
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"User Robot
										association removed successfully."}}</li>
								</ul>
							</li>
							<li>If everything goes fine, robot serial number not provided
								and user-robot association exist
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"User
										association with all robot removed successfully."}}</li>
								</ul>
							</li>
							<li>If everything goes fine and robot association does not exist
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"There is no
										association between provided user and robot"}}</li>
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
							<li>If Email does not exist:
								<ul>
									<li>{"status":-1,"message":"Email does not exist."}</li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
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
				<td class='label_field'>email</td>
				<td class='value_field'><input type="text" name='email' />
				</td>
			</tr>
			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit'
					dummy='disassociateuserfromrobot' value='Submit'
					class='submit_form'>
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


	<form action="<?php echo($baseURL)?>robot.create" method='POST'
		id='robotcreate' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Create Robot</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to create the robots. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.create<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
							<li><b>name</b> :(Optional)Name of the robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"Robot
										created successfully."}}</li>
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
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter name in method
										robot.create"}</li>
								</ul>
							</li>
							<li>If Robot serial number is duplicate
								<ul>
									<li>{"status":-1,"message":"This robot serial number already
										exists."}</li>
								</ul>
							</li>

							<li>If Jabber service is not able to create chat user
								<ul>
									<li>{"status":-1,"message":"Robot could not be created because
										jabber service in not responding."}</li>
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
				<td>name</td>
				<td><input type="text" name='name'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotcreate'
					value='Submit' class='submit_form'>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div class='request_div'>View Request</div> <br />
					<div class='response_div'>View Response</div>
				</td>
			</tr>

		</table>
	</form>


	<form action="<?php echo($baseURL)?>robot.get_details" method='POST'
		id='robotgetdetails' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Get Robot Details</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get the robots detail. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_details<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"id":"65","name":"desk
										cleaner59","serial_number":"robo1","chat_id":"1350924155_robot@rajatogo","chat_pwd":"1350924155_robot"}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and user association exist
								<ul>
									<li>{"status":0,"result":{"id":"68","name":"room
										cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo","chat_pwd":"1350987452_robot","users":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and user association does not exist
								<ul>
									<li>{"status":0,"result":{"id":"70","name":"room
										cleaner","serial_number":"robo1","chat_id":"1351501366_robot@rajatogo","chat_pwd":"1351501366_robot","users":[]}}
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
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.get_details"}</li>
								</ul>
							
							<li>If serial number does not exist
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
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
				<td><input type="button" name='submit' dummy='robotgetdetails'
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


	<form action="<?php echo($baseURL)?>robot.get_associated_users"
		method='POST' id='robotgetusers' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Get Robot associated users details.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get the associated users detail. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_associated_users<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine and user association exist
								<ul>
									<li>
										{"status":0,"result":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and user association does not exist
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
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.get_associated_users"}</li>
								</ul>
							
							<li>If serial number does not exist
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
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
				<td><input type="button" name='submit' dummy='robotgetusers'
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


	<form action="<?php echo($baseURL)?>robot.set_user" method='POST'
		id='robotsetowner' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td colspan="2"><label>Set Robot User</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set the robot user. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.set_user<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User Email ID</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"Robot
										ownership established successfully."}}</li>
								</ul>
							</li>

							<li>If ownership already exists.
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"This robot
										ownership relation already exists."}}</li>
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
							<li>If Email is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter email in method
										robot.set_user"}</li>
								</ul>
							</li>
							<li>If Robot serial number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.set_user"}</li>
								</ul>
						
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
				<td>email</td>
				<td><input type="text" name='email'>
				</td>
			</tr>
			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotsetowner'
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

	<form action="<?php echo($baseURL)?>robot.disassociate_user"
		method='POST' id='disassociaterobotrromuser' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td colspan="2"><label>Disassociate Robot from User or Users</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Disassociate Robot from User or User. <br /> <br />
						URL:
						<?php echo($baseURL)?>
						robot.disassociate_user<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
							<li><b>email</b> :User's Email (If this field is empty, it will
								delete all user association for this particular robot)</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine, user email provided and robot user
								association exist
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"Robot User
										association removed successfully."}}</li>
								</ul>
							</li>
							<li>If everything goes fine, user email not provided and robot
								user association exist
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"Robot
										association with all user removed successfully."}}</li>
								</ul>
							</li>
							<li>If everything goes fine and robot user association does not
								exist
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"There is no
										association between provided robot and user"}}</li>
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
				<td class='label_field'>email</td>
				<td class='value_field'><input type="text" name='email' />
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit'
					dummy='disassociaterobotrromuser' value='Submit'
					class='submit_form'>
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
