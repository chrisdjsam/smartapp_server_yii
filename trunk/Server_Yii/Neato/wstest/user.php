<?php include_once 'common_header.php';?>


<form action="<?php echo($baseURL)?>user.check_for_upgrades" method='POST'
		id='applatestversion' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Check For Upgrades" colspan="2"><label>Check For Upgrades</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get application latest version number and download url.. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.check_for_upgrades<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>app_id</b> :Application ID</li>
							<li><b>current_appversion</b> :Application version on device (Optional)</li>
							<li><b>os_type</b> :Operating system on device (Optional)</li>
							<li><b>os_version</b> :Operating system version on device (Optional)</li>
							   
						</ul>
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"current_app_version":"1.0.0.1","latest_version":"0.5.1.00","latest_version_url":"http:\/\/rajatogo.com\/public_shared\/GTArena_0.5.1.00.apk","upgrade_status":"0"}}
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
							<li>If Application details not found for given ID.
								<ul>
									<li>{"status":-1,"message":"App Id does not exist."}</li>
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
				<td>app_id</td>
				<td><input type="text" name='app_id'></td>
			</tr>

			<tr>
				<td>current_appversion</td>
				<td><input type="text" name='current_appversion'></td>
			</tr>
			
			<tr>
				<td>os_type</td>
				<td><input type="text" name='os_type'></td>
			</tr>
			<tr>
				<td>os_version</td>
				<td><input type="text" name='os_version'></td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='applatestversion'
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


	<form action="<?php echo($baseURL)?>user.create" method='POST'
		id='usercreate' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Create User" colspan="2"><label>Create User</label></td>
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
                                                
                                                Consideration for validation_status which you will get in response.
                                                <ul>
							<li>validation_status: 0 -> Validated - this means that the account has been validated.</li>
                                                        <li>validation_status: -1 -> NotValidatedButInGracePeriod - the user has not been validated, but user is still within the grace period.</li>
                                                        <li>validation_status: -2 -> NotValidated - this email address has not been validated.</li>
                                                </ul>
                        
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"success":true,"guid":1074,"user_handle":"d8828e4ef9596dd0be3b8c4cf0de9502","validation_status":0}}
									</li>
								</ul>
							</li>
							<li>If email exist but the social information does not exist
								<ul>
									<li>{"status":0,"result":{"success":true,"guid":55,"message":"Merged
										user","user_handle":"ce475c5c9b84938f368efe99100b2a11","validation_status":0}}</li>
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
				<td id = "Get User Auth Token" colspan="2"><label>Get User Auth Token</label>
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
                                                        
                                                        <li>If user is within grace period and inactive 
								<ul>
									<li>
                                                                            {"status":0,"result":"eb9cf0c04512903aac873697b33f45cc7e6f8328","extra_params":{"validation_status":-1, "message":"Please activate your account, your account still inactive"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If user is exceed grace period and inactive.
								<ul>
									<li>
                                                                            {"status":0,"result":null,"extra_params":{"validation_status":-2,"message":"Sorry, please Activate your account first and try again."}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If user is active.
								<ul>
									<li>
                                                                            {"status":0,"result":"eb9cf0c04512903aac873697b33f45cc7e6f8328","extra_params":{"validation_status":0,"message":""}}
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
				<td id = "Set User Account Details" colspan="2"><label>Set User Account Details</label></td>
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

	
	<form action="<?php echo($baseURL)?>user.set_attributes"
		method='POST' id='usersetattributes' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id = "Set Attributes" colspan="2"><label>Set Attributes.</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set user's attributes like device type and version. Supported profile keys are 'name', 'operating_system', 'version' <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.set_attributes<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
							<li><b>profile</b> :Map of key=>value pairs, e.g.
								profile{'operating_system'=>'Android',
								'version'='4.0'}</li>
						</ul>
						Success Response:
						<ul>
							<li>{"status":0,"result":{"success":true,"message":"User attributes are set successfully."}}</li>
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
									<li>{"status":-1,"message":"Method call failed the User Authentication"}</li>
								</ul>
							</li>
							
							<li>If value not provided for profile key:
								<ul>
									<li>{"status":-1,"message":"Invalid value for key operating_system."}</li>
								</ul>
							</li>
							
							<li>If problem in setting user attributes:
								<ul>
									<li>{"status":-1,"message":"Error in setting user attributes."}</li>
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
				<td>auth_token</td>
				<td><input type="text" name='auth_token'></td>
			</tr>
			<tr>
				<td id='labelPlaceholderRow1' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value='' id='labelName1'
					class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLink1'>Add device attribute Key (considered keys are
						operating_system, version)</div>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit' dummy='usersetattributes'
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
	
	
	<form action="<?php echo($baseURL)?>user.get_attributes"
		method='POST' id='usergetattributes' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id = "Get Attributes" colspan="2"><label>Get Attributes.</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get user's attributes like device type and version. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.get_attributes<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>auth_token</b> :User's auth token (received from get user
								handle call)</li>
						</ul>
						Success Response:
						<ul>
							<li>{"status":0,"result":{"success":true,"user_attributes":{"name":"mac","operating_system":"","version":""}}}</li>
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
									<li>{"status":-1,"message":"Method call failed the User Authentication"}</li>
								</ul>
							</li>
							
							<li>If Attributes are not set:
								<ul>
									<li>{"status":-1,"message":"Attributes not found for this user"}</li>
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
				<td>auth_token</td>
				<td><input type="text" name='auth_token'></td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='usergetattributes'
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
	
	
	
	<form action="<?php echo($baseURL)?>user.change_password"
		method='POST' id='changepassword' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td id = "Change Password" colspan="2"><label>Change Password</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Change Password. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.change_password<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>auth_token</b> :User's auth_token</li>
							<li><b>password_old</b> :User's old password</li>
							<li><b>password_new</b> :User's new password</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"Your password is changed successfully."}}</li>
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
							<li>If Auth token does not exist:
								<ul>
									<li>{"status":-1,"message":"User could not be authenticated."}</li>
								</ul>
							</li>
							<li>If old password does not match with user's existing password:
								<ul>
									<li>{"status":-1,"message":"Old password does not match with user password."}</li>
								</ul>
							</li>
							
							<li>If new password is empty or has only spaces:
						 		<ul>
						 			<li>{"status":-1,"message":"Password should contain atleast one character."}</li>
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
				<td>password_old</td>
				<td><input type="text" name='password_old'>
				</td>
			</tr>
			
			<tr>
				<td>password_new</td>
				<td><input type="text" name='password_new'>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='changepassword'
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
	
	<form action="<?php echo($baseURL)?>user.forget_password"
		method='POST' id='forgetpassword' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td id = "Forget Password" colspan="2"><label>Forget Password</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Forget Password. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.forget_password<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User's email address</li>
						</ul>
						Success Response:

						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"New password is sent to your email."}}</li>
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
							
							<li>If email address not found in database:
								<ul>
									<li>{"status":-1,"message":"Email does not exist."}</li>
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
				<td>email</td>
				<td><input type="text" name='email'>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='forgetpassword'
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
	
	
	
	
	<form action="<?php echo($baseURL)?>user.get_user_account_details"
		method='POST' id='usergetuseraccountdetails' class='ajaxified_forms'>
		<table class='custom_table newaddition'>
			<tr>
				<td id = "Get User Account Details" colspan="2"><label>Get User Account Details</label>
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
						Success Responses:

						<ul>
							<li>If everything goes fine and social information does not exist
								<ul>
									<li>
										{"status":0,"result":{"id":367,"name":"pradip37","email":"pradip_patro378@gmail.com","chat_id":"1350922773_user@rajatogo","chat_pwd":"1350922773_user","social_networks":[],"validation_status":0}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and social information exists
								<ul>
									<li>
										{"status":0,"result":{"id":357,"name":"pradip3","email":"pradip_patro3@gmail.com","chat_id":"1350911036_user@rajatogo","chat_pwd":"1350911036_user","social_networks":[{"provider":"Facebook"},{"external_social_id":"123456789"}],"validation_status":0}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine,social information does not exist and robot association exists
								<ul>
									<li>
										{"status":0,"result":{"id":542,"name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo","chat_pwd":"1351499916_user","social_networks":[],"robots":[{"id":"68","name":"room
										cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo"},{"id":"69","name":"desk
										cleaner60","serial_number":"robo6","chat_id":"1350991375_robot@rajatogo"}],"validation_status":0}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and both social information and robot association do not exist
								<ul>
									<li>
										{"status":0,"result":{"id":543,"name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo","chat_pwd":"1351500158_user","social_networks":[],"robots":[],"validation_status":0}}
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
				<td id = "Get User associated robots" colspan="2"><label>Get User associated robots</label>
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
						Success Responses:

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
				<td id = "Update User Auth Token Expiry" colspan="2"><label>Update User Auth Token Expiry</label>
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
				<td id = "Logout User Auth Token" colspan="2"><label>Logout User Auth Token</label>
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
				<td id = "Disassociate User from Robot or Robots"  colspan="2"><label>Disassociate User from Robot or Robots</label>
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
						Success Responses:

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


	<form action="<?php echo($baseURL)?>user.create2" method='POST'
		id='usercreate2' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Create User 2" colspan="2"><label>Create User 2</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to create the users with or without social networking
						information. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.create2<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>name</b> :Name of the user</li>
							<li><b>email</b> :Email of the user</li>
                                                        <li><b>alternate_email</b> :Alternate Email of the user</li>
							<li><b>password</b> :Password of the user. It does not need to be
								unique.</li>
							<li><b>account_type</b> :Native OR Facebook (OR Google etc)</li>
							<li><b>external_social_id</b> :External Social ID (e.g. Facebook
								ID (numeric value) that is returned by the Facebook). This is
								required ONLY when the account type is NOT Native.</li>
						</ul>
                                                
                                                Consideration for validation_status which you will get in response.
                                                <ul>
							<li>validation_status: 0 -> Validated - this means that the account has been validated.</li>
                                                        <li>validation_status: -1 -> NotValidatedButInGracePeriod - the user has not been validated, but user is still within the grace period.</li>
                                                        <li>validation_status: -2 -> NotValidated - this email address has not been validated.</li>
                                                </ul>                                                
                                                
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"success":true,"guid":1074,"user_handle":"d8828e4ef9596dd0be3b8c4cf0de9502","validation_status":-1}}
									</li>
								</ul>
							</li>
							<li>If email exist but the social information does not exist
								<ul>
									<li>{"status":0,"result":{"success":true,"guid":55,"message":"Merged
										user","user_handle":"ce475c5c9b84938f368efe99100b2a11","validation_status":0}}</li>
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
							</li>
							<li>If Email does not valid
								<ul>
									<li>{"status":-1,"message":"The email address you provided does
										not appear to be a valid email address."}</li>
								</ul>
							</li>
                                                        <li>If Alternate Email does not valid
								<ul>
									<li>{"status":-1,"message":"The alternate email address you provided does
										not appear to be a valid email address."}</li>
								</ul>
							</li>
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
				<td>alternate_email</td>
				<td><input type="text" name='alternate_email'></td>
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
				<td><input type="button" name='submit' dummy='usercreate2'
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





	<form action="<?php echo($baseURL)?>user.IsUserValidated" method='POST'
		id='IsUserValidated' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Is User Validated" colspan="2"><label>Is User Validated?</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to check whether user's email is validated or not <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.IsUserValidated<br /> Parameters:
						<ul>
							
                                                        <li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :Email of the user</li>
							
						</ul>
						Success Responses:
						<ul>
							<li>If email is active
								<ul>
									<li>
										{"status":0,"result":{"validation_status":0,"message":"The email address you have provided is Active"}}
									</li>
								</ul>
							</li>
							<li>If email is inactive and within grace period
								<ul>
									<li>
                                                                            {"status":0,"result":{"validation_status":-1,"message":"The email address you have provided does not appear to be a validated. Please validate it."}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If email is inactive and cross grace period
								<ul>
									<li>
                                                                            {"status":0,"result":{"validation_status":-2,"message":"Sorry, You must validate your account to proceed."}}
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
							<li>If email is missing or invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided does not appear to be a valid email address."}
                                                                        </li>
								</ul>
							</li>
							<li>If provided email does not exist in database
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided does not exist in our system."}
                                                                        </li>
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
				<td>email</td>
				<td><input type="text" name='email'></td>
			</tr>
                        
			<tr>
				<td><input type="button" name='submit' dummy='IsUserValidated'
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


	<form action="<?php echo($baseURL)?>user.ResendValidationEmail" method='POST'
		id='ResendValidationEmail' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Resend Validation Email" colspan="2"><label>Resend Validation Email</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Resend Validation Email <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.ResendValidationEmail<br /> Parameters:
						<ul>

                                                        <li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :Email of the user</li>

						</ul>
						Success Responses:
						<ul>
							<li>If resent validation email
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":"We have resent validation email"}}
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
							<li>If email is missing or invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided does not appear to be a valid email address."}
                                                                        </li>
								</ul>
							</li>
							<li>If provided email does not exist in database
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided does not exist in our system."}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If resend limit exceeds
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, You crossed resend validation email limit."}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If provided email already activated
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided is already activated."}
                                                                        </li>
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
				<td>email</td>
				<td><input type="text" name='email'></td>
			</tr>
                        
			<tr>
				<td><input type="button" name='submit' dummy='ResendValidationEmail'
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




<?php include_once 'common_footer.php';?>