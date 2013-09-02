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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a parameter(name) is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter name in method robot.create","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If Robot serial number is duplicate
								<ul>
									<li>
                                                                            {"status":-1,"message":"This robot serial number already exists.", "error":{"code":"-118","message":"This robot serial number already exists."}}
                                                                        </li>
								</ul>
							</li>

							<li>If Jabber service is not able to create chat user
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot could not be created because jabber service in not responding.", "error":{"code":-108, "message":"Robot could not be created because jabber service in not responding."}}
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.is_online","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Serial number does not exist", "error":{"code":"-114","message":"Serial number does not exist."}}
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
	

<!--	<form action="<?php echo($baseURL)?>robot.set_profile_details"
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>

							<li>If serial_number is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_profile_details", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>

							<li>If profile key is not added:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter profile in method robot.set_profile_details","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If Robot serial number does not exist
                                                                <ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist","error":{"code":"-114","message":"Serial number does not exist."}}
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
					<div id='addLabelLink'>Add Profile Detail Key</div>
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
	</form>-->


<!--	<form action="<?php echo($baseURL)?>robot.set_profile_details2"
		method='POST' id='robotsetprofiledetails2' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id="Set Robot Profile Details 2" colspan="2"><label>Set Robot Profile Details 2</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set robot's profile details 2. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.set_profile_details2<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
                                                        <li><b>source_serial_number</b> :if sent from robot, contains the robot's serial id (can be empty)</li>
                                                        <li><b>source_smartapp_id</b> :if sent from the smartapp, contains the user email (can be empty)</li>
                                                        <li><b>value_extra</b> :an optional JSON string</li>
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>

							<li>If serial_number is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_profile_details2", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If serial number does not exist
                                                                 <ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
                                                                        </li>
                                                                 </ul>
                                                        </li>

							<li>If source_serial_number or source_smartapp_id is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please provide atleast one source(source_serial_number or source_smartapp_id)", "error":{"code":"-148","message":"Missing parameter source serial number or source smartapp id in method call"}}
                                                                        </li>
								</ul>
							</li>                                                        
                                                        
							<li>If source_smartapp_id is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid email address in field source_smartapp_id.", "error":{"code":"-149","message":"Please enter valid email id in the field source smartapp id"}}
                                                                        </li>
								</ul>
							</li>                                                                                                                
                                                        
							<li>If source_smartapp_id does not exist:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) does not exist in our system.","error":{"code":"-150","message":"Sorry, Provided source_smartapp_id(email) does not exist in our system"}}
                                                                        </li>
								</ul>
							</li>                                                                                                                                                                        

							<li>If source_smartapp_id(email) is not associated with given robot:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot","error":{"code":"-151","message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot"}}
                                                                        </li>
								</ul>
							</li>                                                                                                                                                                        
                                                        
                                                        <li>If entered key is invalid:
                                                                <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Sorry, entered key is invalid","error":{"code":"-152","message":"Sorry, entered key does not match with serial number."}}
                                                                        </li>
                                                                </ul>
                                                        </li>
                                                        
							<li>If profile key is not added:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter profile in method robot.set_profile_details2","error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td>source_serial_number</td>
				<td>
                                    <input type="text" name='source_serial_number'>
				</td>
			</tr>
			<tr>
				<td>source_smartapp_id</td>
				<td>
                                    <input type="text" name='source_smartapp_id'>
				</td>
			</tr>                        
			<tr>
				<td>value_extra</td>
				<td>
                                    <input type="text" name='value_extra'>
				</td>
			</tr>                                                
			<tr>
				<td id='labelPlaceholderRow3' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value='' id='labelName3'
					class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLink3'>Add Profile Detail Key</div>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit'
					dummy='robotsetprofiledetails2' value='Submit' class='submit_form'>
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
	</form>-->


	<form action="<?php echo($baseURL)?>robot.set_profile_details3"
		method='POST' id='robotsetprofiledetails3' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id="Set Robot Profile Details 3" colspan="2"><label>Set Robot Profile Details 3</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set robot's profile details 3. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.set_profile_details3<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
                                                        <li><b>source_serial_number</b> :if sent from robot, contains the robot's serial id (can be empty)</li>
                                                        <li><b>source_smartapp_id</b> :if sent from the smartapp, contains the user email (can be empty)</li>
                                                        <li><b>cause_agent_id</b> :Unique sender ID</li>
                                                        <li><b>value_extra</b> :an optional JSON string</li>
                                                        <li><b>notification_flag</b> :Notification Flag (Default value is true)</li>
							<li><b>profile</b> :Map of key=>value pairs, e.g.
								profile{'name'=>'room cleaner'}</li>
						</ul>
						Success Response:
						<ul>
							<li>{"status":0,"result":1,"extra_params":{"expected_time":1,"timestamp":1369377928}}</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>

							<li>If serial_number is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_profile_details3", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>

							<li>If source_serial_number or source_smartapp_id is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please provide atleast one source(source_serial_number or source_smartapp_id)","error":{"code":"-148","message":"Missing parameter source serial number or source smartapp id in method call"}}
                                                                        </li>
								</ul>
							</li>                                                        
                                                        
							<li>If source_smartapp_id is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid email address in field source_smartapp_id.","error":{"code":"-149","message":"Please enter valid email id in the field source smartapp id"}}
                                                                        </li>
								</ul>
							</li>                                                                                                                
                                                        
							<li>If source_smartapp_id does not exist:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) does not exist in our system.","error":{"code":"-150","message":"Sorry, Provided source_smartapp_id(email) does not exist in our system"}}
                                                                        </li>
								</ul>
							</li>                                                                                                                                                                        

							<li>If source_smartapp_id(email) is not associated with given robot:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot","error":{"code":"-151","message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If cause_agent_id is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter cause_agent_id in method robot.set_profile_details3","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
							<li>If profile key is not added:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter profile in method robot.set_profile_details3","error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td>source_serial_number</td>
				<td>
                                    <input type="text" name='source_serial_number'>
				</td>
			</tr>
			<tr>
				<td>source_smartapp_id</td>
				<td>
                                    <input type="text" name='source_smartapp_id'>
				</td>
			</tr>                        
                        <tr>
				<td>cause_agent_id</td>
				<td>
                                    <input type="text" name='cause_agent_id'>
				</td>
			</tr>                        
			<tr>
				<td>value_extra</td>
				<td>
                                    <input type="text" name='value_extra'>
				</td>
			</tr>                                                
                        <tr>
				<td>notification_flag</td>
				<td>
                                    <input type="text" name='notification_flag'>
                                    <span style="color: blue;">(Consideration: 0 for false and 1 for true)</span>
				</td>
			</tr>                                                
			<tr>
				<td id='labelPlaceholderRow4' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value='' id='labelName4'
					class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLink4'>Add Profile Detail Key</div>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit'
					dummy='robotsetprofiledetails3' value='Submit' class='submit_form'>
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


	<form action="<?php echo($baseURL)?>robot.get_profile_details"
		method='POST' id='robotgetprofiledetails' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id="Get Robot Profile Details" colspan="2"><label>Get Robot
						Profile Details</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot's profile details. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_profile_details<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
                                                        <li><b>key</b> :Key</li>
						</ul>
						Success Response:
						<ul>
                                                        <li>If enter serial number as 1 and key as dark:
								<ul>
									<li>{"status":0,"result":{"success":true,"profile_details":{"name":"robot 1","serial_number":"1","dark":"knight"}}}</li>
								</ul>
							</li>
                                                        <li>If enter serial number as 1 and blank key:
								<ul>
									<li>{"status":0,"result":{"success":true,"profile_details":{"name":"robot 1","serial_number":"1","real":"steel","dark":"knight"}}}</li>
								</ul>
							</li>                                                        
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>

							<li>If serial_number is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_profile_details", "error":{"code":"-102","message":"Missing parameter in method call"}}}
                                                                        </li>
								</ul>
							</li>
                                                        
							<li>If serial_number is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114, "message":"Robot serial number does not exist"}}
                                                                        </li>
								</ul>
							</li>                                                        
                                                        
							<li>If key is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, entered key is invalid","error":{"code":"-152","message":"Sorry, entered key does not match with serial number."}}
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
				<td>serial_number</td>
				<td>
                                    <input type="text" name='serial_number'>
                                </td>
			</tr>
			<tr>
				<td>key</td>
				<td>
                                    <input type="text" name='key'>
                                </td>
			</tr>
                        
			<tr>
				<td>
                                    <input type="button" name='submit' dummy='robotgetprofiledetails' value='Submit' class='submit_form'>
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


	<form action="<?php echo($baseURL)?>robot.get_profile_details2"
		method='POST' id='robotgetprofiledetails2' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id="Get Robot Profile Details 2" colspan="2"><label>Get Robot Profile Details 2</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot's profile details 2. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_profile_details<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
                                                        <li><b>key</b> :Key</li>
						</ul>
						Success Response:
						<ul>
                                                        <li>If enter serial number as 1 and key as dark:
								<ul>
									<li>{"status":0,"result":{"success":true,"profile_details":{"name":{"value":"robot 1","timestamp":0},"serial_number":{"value":"1","timestamp":0},"dark":{"value":"knight","timestamp":"1368090063"}}}}</li>
								</ul>
							</li>
                                                        <li>If enter serial number as 1 and blank key:
								<ul>
									<li>{"status":0,"result":{"success":true,"profile_details":{"name":{"value":"robot 1","timestamp":0},"serial_number":{"value":"1","timestamp":0},"real":{"value":"steel","timestamp":"1368090063"},"dark":{"value":"knight","timestamp":"1368090063"}}}}</li>
								</ul>
							</li>                                                        
					
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>

							<li>If serial_number is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_profile_details2", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
							<li>If serial_number is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
                                                                        </li>
								</ul>
							</li>                                                        
                                                        
							<li>If key is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, entered key is invalid","error":{"code":"-152","message":"Sorry, entered key does not match with serial number."}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If source_serial_number or source_smartapp_id is missing:
                                                                <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Please provide atleast one source(source_serial_number or source_smartapp_id)", "error":{"code":"-148","message":"Missing parameter source serial number or source smartapp id in method call"}}
                                                                        </li>
                                                                </ul>
                                                        </li>
                                                        
                                                        <li>If source smartapp id does not exist
                                                                <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) does not exist in our system.","error":{"code":"-150","message":"Sorry, Provided source_smartapp_id(email) does not exist in our system"}}
                                                                        </li>
                                                                </ul>
                                                        </li>
                                                        
                                                        <li>If source smartapp id is incorrect
                                                                <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Please enter valid email address in field source_smartapp_id.", "error":{"code":"-149","message":"Please enter valid email id in the field source smartapp id"}}
                                                                        </li>
                                                                </ul>
                                                        </li>
                                                        
                                                        <li>If source smartapp id is not associated with any robot
                                                                <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot","error":{"code":"-151","message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot"}}
                                                                        </li>
                                                                </ul>
                                                        </li>
                                                        
                                                        <li>If profile key is not added 
                                                                <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Missing parameter profile in method robot.set_profile_details2","error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>serial_number</td>
				<td>
                                    <input type="text" name='serial_number'>
                                </td>
			</tr>
			<tr>
				<td>key</td>
				<td>
                                    <input type="text" name='key'>
                                </td>
			</tr>
                        
			<tr>
				<td>
                                    <input type="button" name='submit' dummy='robotgetprofiledetails2' value='Submit' class='submit_form'>
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


	

	<form action="<?php echo($baseURL)?>robot.delete_robot_profile_key2"
		method='POST' id='delete_robot_profile_key2' class='ajaxified_forms'>
		<table class='custom_table'>
			<tr>
				<td id="Delete Robot Profile Key 2" colspan="2"><label>Delete Robot Profile Key 2</label></td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete robot's profile key. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.delete_robot_profile_key2<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
                                                        <li><b>key</b> :Key</li>
                                                        <li><b>cause_agent_id</b> :Cause Agent Id</li>
                                                        <li><b>source_serial_number</b> :Source Serial Number</li>
                                                        <li><b>source_smartapp_id</b> :Source Smartapp Id</li>
                                                        <li><b>notification_flag</b> :Notification Flag (Default value is true)</li>

						</ul>
						Success Response:
						<ul>                                                        
                                                        <li>{"status":0,"result":{"success":true}}</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing or not correct:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>

							<li>If serial_number is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.delete_robot_profile_key2", "error":{"code":"-102","message":"Missing parameter in method call"}}
									</li>
								</ul>
							</li>
                                                        
                                                        <li>If key is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter key in method robot.delete_robot_profile_key2", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If cause_agent_id is not provided:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter cause_agent_id in method robot.delete_robot_profile_key2", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
							<li>If serial_number is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
                                                                        </li>
								</ul>
							</li>                                                        
                                                        
							<li>If key is invalid:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, entered key is invalid", "error":{"code":"-152","message":"Sorry, entered key does not match with serial number."}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If source smartapp id is incorrect
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid email address in field source_smartapp_id.", "error":{"code":"-149","message":"Please enter valid email id in the field source smartapp id"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If source smartapp id does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) does not exist in our system.", "error":{"code":"-150","message":"Sorry, Provided source_smartapp_id(email) does not exist in our system"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If Provided source_smartapp_id(email) is not associated with given robot
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot","error":{"code":"-151","message":"Sorry, Provided source_smartapp_id(email) is not associated with given robot"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
                                </td>
			</tr>
                        
			<tr>
				<td>serial_number</td>
				<td>
                                    <input type="text" name='serial_number'>
                                </td>
			</tr>
			<tr>
				<td>key</td>
				<td>
                                    <input type="text" name='key'>
                                </td>
			</tr>
                        <tr>
				<td>cause_agent_id</td>
				<td>
                                    <input type="text" name='cause_agent_id'>
				</td>
			</tr>
                        <tr>
				<td>source_serial_number</td>
				<td>
                                    <input type="text" name='source_serial_number'>
				</td>
			</tr>
			<tr>
				<td>source_smartapp_id</td>
				<td>
                                    <input type="text" name='source_smartapp_id'>
				</td>
			</tr>                        
                        <tr>
				<td>notification_flag</td>
				<td>
                                    <input type="text" name='notification_flag'>
                                    <span style="color: blue;">(Consideration: 0 for false and 1 for true)</span>
				</td>
			</tr>                        
			<tr>
				<td>
                                    <input type="button" name='submit' dummy='delete_robot_profile_key2' value='Submit' class='submit_form'>
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
									<li>{
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_details", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Serial number does not exist", "error":{"code":-114, "message":"Serial number does not exist"}}
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a parameter(serial_number) is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_associated_users", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Serial number does not exist", "error":{"code":-114, "message":"Serial number does not exist"}}
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If Email is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter email in method robot.set_user", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If Robot serial number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_user", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
                                                        </li>
                                                        
                                                        <li>If email does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Email does not exist","error":{"code":"-112","message":"Email does not exist."}}
                                                                        </li>
								</ul>
                                                        </li>
                                                        
                                                        <li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist","error":{"code":"-114","message":"Serial number does not exist."}}
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist","error":{"code":"-114","message":"Serial number does not exist."}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If serial number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Serial number does not exist", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If email is invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"Email does not exist.","error":{"code":"-112","message":"Email does not exist."}}
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If parameter serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_details", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114, "message":"Robot serial number does not exist"}}
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
	
	
	<form action="<?php echo($baseURL)?>robot.get_robot_presence_status" method='POST'
		id='getRobotPresenceStatus' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Get Robot Presence Status" colspan="2"><label>Get Robot Presence Status</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot presence status. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_robot_presence_status<br /> 
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_robot_presence_status", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit' dummy='getRobotPresenceStatus'
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


	<form action="<?php echo($baseURL)?>robot.ping_from_robot" method='POST'
		id='pingFromRobot' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Ping From Robot" colspan="2"><label>Ping From Robot</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to ping from robot. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.ping_from_robot<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
                                                        <li><b>status</b> :status of robot(optional)</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"robot ping have been recorded"}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.ping_from_robot", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>

			<tr>
				<td>status</td>
				<td><input type="text" name='status'>
				</td>
			</tr>                        
                        
			<tr>
				<td><input type="button" name='submit' dummy='pingFromRobot'
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

	<form action="<?php echo($baseURL)?>robot.is_robot_online_virtual" method='POST'
		id='isRobotOnlineVirtual' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Is Robot Online Virtual" colspan="2"><label>Is Robot Online Virtual</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to check whether robot is virtually online or not. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.is_robot_online_virtual<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine and robot is online
								<ul>
									<li>{"status":0,"result":{"online":true,"message":"Robot 1 is online.","expected_time":12}}</li>
								</ul>
							</li>
							<li>If everything goes fine and robot is offline
								<ul>
									<li>{"status":0,"result":{"online":false,"message":"Robot 1 is offline.","expected_time":-12}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.is_robot_online_virtual", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit' dummy='isRobotOnlineVirtual'
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


	<form action="<?php echo($baseURL)?>robot.get_robot_type_metadata_using_type" method='POST'
		id='get_robot_type_metadata_using_type' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Get Robot Type Metadata Using Robot Type" colspan="2"><label>Get Robot Type Metadata Using Robot Type</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot type metadata using robot type. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_robot_type_metadata_using_type<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_type</b> :Your Robot's Type</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"robot_metadata":{"type":"1","metadata":{"sleep_time":"120","lag_time":"15"}}}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>

							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a robot_type is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter robot_type in method robot.get_robot_type_metadata_using_type", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If entered robot_type is invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"Provided robot type is not valid", "error":{"code":"-142","message":"Robot Type is not valid"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>robot_type</td>
				<td><input type="text" name='robot_type'>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit' dummy='get_robot_type_metadata_using_type'
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


	<form action="<?php echo($baseURL)?>robot.get_robot_type_metadata_using_id" method='POST'
		id='get_robot_type_metadata_using_id' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Get Robot Type Metadata Using Robot Id" colspan="2"><label>Get Robot Type Metadata Using Robot Id</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot type metadata using robot Id. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_robot_type_metadata_using_id<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
						</ul>
						Success Response:
						<ul>
                                                        <li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"robot_metadata":{"type":"1","metadata":{"sleep_time":"120","lag_time":"15"}}}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_robot_type_metadata_using_id", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>

			<tr>
				<td><input type="button" name='submit' dummy='get_robot_type_metadata_using_id'
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


	<form action="<?php echo($baseURL)?>robot.set_robot_configuration" method='POST'
		id='set_robot_configuration' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Set Robot Configuration" colspan="2"><label>Set Robot Configuration</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set robot configuration. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.set_robot_configuration<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
                                                        <li><b>sleep_time</b> :Sleep Time of the robot in seconds</li>
                                                        <li><b>wakeup_time</b> :Wakeup time of the robot in seconds</li>
                                                        <li><b>config_key_value</b> :Robot Configuration Key Value (Optional)</li>
						</ul>
						Success Response:
						<ul>
                                                        <li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"timestamp":1370422277}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.set_robot_configuration", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter sleep_time in method robot.set_robot_configuration", "error":{"code":"-114","message":"Serial number does not exist."}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If provided sleep time or wakeup time is invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid sleep time or wakeup time","error":{"code":"-156","message":"Please enter valid sleep time or wakeup time"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>

			<tr>
				<td>sleep_time</td>
				<td><input type="text" name='sleep_time'>
				</td>
			</tr>

			<tr>
				<td>wakeup_time</td>
				<td><input type="text" name='wakeup_time'>
				</td>
			</tr>
                        
                        <tr>
				<td id='labelPlaceholderRow5' colspan="2"></td>
			</tr>
			<tr>
				<td><input type="text" name='labelName' value='' id='labelName5'
					class='removeFromRequest'>
				</td>
				<td>
					<div id='addLabelLink5'>Add Robot Config Key</div>
				</td>
			</tr>
                        
			<tr>
				<td><input type="button" name='submit' dummy='set_robot_configuration'
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

	<form action="<?php echo($baseURL)?>robot.get_robot_configuration" method='POST'
		id='get_robot_configuration' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Get Robot Configuration" colspan="2"><label>Get Robot Configuration</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot configuration. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_robot_configuration<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
						</ul>
						Success Response:
						<ul>
                                                        <li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"serial_number":"1","sleep_time":"1","wakeup_time":"100","timestamp":"1370527227","config_key_value":{"real":"mask","real1":"steal1","k1":"v1","k2":"v2"}}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_robot_configuration", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
                        
			<tr>
				<td><input type="button" name='submit' dummy='get_robot_configuration'
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

	<form action="<?php echo($baseURL)?>robot.get_token_for_robot_user_association" method='POST'
		id='get_token_for_robot_user_association' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Get Token for Robot-User Association" colspan="2"><label>Get Token for Robot-User Association</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Get Token for Robot-User Association. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_token_for_robot_user_association<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of the robot</li>
						</ul>
                                                
                                                Note:
                                                <ul>
							<li>Token will be expired after 5 minutes.</li>
						</ul>
                                                
						Success Response:
						<ul>
                                                        <li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"token":"6a077b7c2fee661ffa43d35bdca0e286"}}</li>
								</ul>
							</li>
						</ul>

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial_number is missing
								<ul>
									<li>
                                                                             {"status":-1,"message":"Missing parameter serial_number in method robot.get_token_for_robot_user_association", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If serial number does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114,"message":"Robot serial number does not exist"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
                        
			<tr>
				<td><input type="button" name='submit' dummy='get_token_for_robot_user_association'
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

	<form action="<?php echo($baseURL)?>robot.robot_user_association_by_token" method='POST'
		id='robot_user_association_by_token' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id="Robot-User Association by Token" colspan="2"><label>Robot-User Association by Token</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to set Robot-User Association by Token. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.robot_user_association_by_token<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>email</b> :User email address</li>
                                                        <li><b>token</b> :Token which is get in 'Robot-User Association by Token' API</li>
						</ul>
						Success Response:
						<ul>
                                                        <li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"Robot-User association is done successfully"}}</li>
								</ul>
							</li>
                                                        
                                                        <li>If user is already associated,
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"User robot association already exists."}}</li>
								</ul>
							</li>
                                                        
						</ul>
                                                

						Failure Responses: <br />
						<ul>
							<li>If API Key is missing:

								<ul>
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a email is missing
								<ul>
									<li>
                                                                             {"status":-1,"message":"Missing parameter email in method robot.robot_user_association_by_token", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							<li>If email does not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, provided email does not exist", "error":{"code":"-112","message":"Email does not exist."}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If a token is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter token in method robot.robot_user_association_by_token","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If provide token is invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid token","error":{"code":"-154","message":"Please enter valid token"}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If provide email is invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid email address", "error":{"code":"-105","message":"The email address you provided does not appear to be a valid email address."}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If provide token is expired
								<ul>
									<li>
                                                                            {"status":-1,"message": "Sorry, provided token is expired","error":{"code":"-155","message":"Sorry, provided token is expired"}}
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
					class='api_keys' value='<?php echo($api_key);?>' />
				</td>
			</tr>

			<tr>
				<td>email</td>
				<td><input type="text" name='email'>
				</td>
			</tr>
                        
			<tr>
				<td>token</td>
				<td><input type="text" name='token'>
				</td>
			</tr>                        
                        
			<tr>
				<td><input type="button" name='submit' dummy='robot_user_association_by_token'
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

<?php include_once 'common_footer.php';?>