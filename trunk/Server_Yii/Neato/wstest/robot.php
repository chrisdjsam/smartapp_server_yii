<?php include_once 'common_header.php';?>
	<form action="<?php echo($baseURL)?>robot.create" method='POST'
		id='robotcreate' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Create Robot" colspan="2"><label>Create Robot</label>
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
	
	
	<form action="<?php echo($baseURL)?>robot.is_online" method='POST'
		id='isrobotonline' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Check if robot is online" colspan="2"><label>Check if robot is online</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to check if robot online. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.is_online<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine and robot is online
								<ul>
									<li>{"status":0,"result":{"online":true,"message":"Robot 1234 is online."}}</li>
								</ul>
							</li>
							<li>If everything goes fine and robot is offline
								<ul>
									<li>{"status":0,"result":{"online":false,"message":"Robot 1234 is offline."}}</li>
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
							<li>If a serial_number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in method
										robot.is_robot_online"}</li>
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
				<td><input type="button" name='submit' dummy='isrobotonline'
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
	


	<form action="<?php echo($baseURL)?>robot.set_profile_details"
		method='POST' id='robotsetprofiledetails' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id="Set Robot Profile Details" colspan="2"><label>Set Robot
						Profile Details</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set robot's profile details. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.set_profile_details<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
							<li><b>profile</b> :Map of key=>value pairs, e.g.
								profile{'name'=>'room cleaner'}</li>
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

							<li>If serial_number is not provided:
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.set_profile_details"}</li>
								</ul>
							</li>

							<li>If profile key is not added:
								<ul>
									<li>{"status":-1,"message":"Missing parameter profile in method
										robot.set_profile_details"}</li>
								</ul>
							</li>

							<li>If key is added but value is not provided :
								<ul>
									<li>{"status":-1,"message":"Invalid value for key name."}</li>
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
					<div id='addLabelLink'>Add Profile Detail Key (considered key is
						name)</div>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit'
					dummy='robotsetprofiledetails' value='Submit' class='submit_form'>
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


	<form action="<?php echo($baseURL)?>robot.get_details" method='POST'
		id='robotgetdetails' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Get Robot Details" colspan="2"><label>Get Robot Details</label>
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
						Success Responses:
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
				<td id="Get Robot associated users details" colspan="2"><label>Get
						Robot associated users details</label>
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
						Success Responses:
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
				<td id="Set Robot User" colspan="2"><label>Set Robot User</label>
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
						Success Responses:
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
				<td id="Disassociate Robot from User or Users" colspan="2"><label>Disassociate
						Robot from User or Users</label>
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
						Success Responses:

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

	
		<form action="<?php echo($baseURL)?>robot.delete" method='POST'
		id='robotdelete' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Delete Robot" colspan="2"><label>Delete Robot</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete robot. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.delete<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
						</ul>
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have deleted robot 123 successfully"}}
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
							<li>If parameter serial_number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.get_details"}</li>
								</ul>
							
							<li>If serial number does not exist
								<ul>
									<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
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
				<td><input type="button" name='submit' dummy='robotdelete'
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
	
	
	
<?php include_once 'common_footer.php';?>