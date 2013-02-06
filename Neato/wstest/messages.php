<?php include_once 'common_header.php';?>
	<form action="<?php echo($baseURL)?>message.send_xmpp_message_to_robot" method='POST'
		id='sendmessage' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Send Xmpp Message To Robot" colspan="2"><label>Send Xmpp Message To Robot.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to send xmpp message to robot. <br /> <br /> URL:
						<?php echo($baseURL)?>
						user.send_xmpp_message_to_robot<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> 		:Your API Key</li>
							<li><b>user_id</b> 		:ID of user sending message.</li>
							<li><b>serial_number</b>:serial_number of robot to whom user want to send message.</li>
							<li><b>message</b> 		:Message Text</li>
							</li>
						</ul>
				

						Success Response:
						<ul>
							<li>If message is sent to robot
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":"Message is sent to robot 1."}}
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
							
							<li>If serial_nubmer not found:
								<ul>
									<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
								</ul>
							</li>
							
							<li>If user_id not found:
								<ul>
									<li>{"status":-1,"message":"User ID does not exist"}</li>
								</ul>
							</li>
							
							<li>If user and robot association not exist:
								<ul>
									<li>{"status":-1,"message":"User robot association does not exist."}</li>
								</ul>
							</li>
							
							<li>If message sending failed:
								<ul>
									<li>{"status":-1,"message":"Message could not be sent to robot sr1."}</li>
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
				<td>user_id</td>
				<td><input type="text" name='user_id'>
				</td>
			</tr>
			<tr>
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
			<tr>
				<td>message</td>
				<td><textarea rows="5" cols="20" name='message'></textarea>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='sendmessage'
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
	
	
	
	<form action="<?php echo($baseURL)?>message.send_message_to_associated_users" method='POST'
		id='informall' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Send Message To Associated Users" colspan="2"><label>Send Message To Associated Users.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to send message to one or more users. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.send_message_to_associated_users<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> 		:Your API Key</li>
							<li><b>serial_number</b>:Serial number of robot sending robot.</li>
							<li><b>message_type</b> :Type of message (Only XMPP is supported for now).</li>
							<li><b>message</b> 		:Message Text.</li>
						
						</ul>
				

						Success Response:
						<ul>
							<li>If message is sent to user(s)
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":"Message is sent to 3 user(s)."}}
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
							
							<li>If sending failed:
								<ul>
									<li>{"status":-1,"message":"Message is sent to 0 user(s)."}</li>
								</ul>
							</li>
							
							<li>If serial_nubmer not found:
								<ul>
									<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
								</ul>
							</li>
							
							<li>If message_type does not match:
								<ul>
									<li>{"status":-1,"message":"XMP does not match supported message type XMPP"}</li>
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
				<td>message_type</td>
				<td><input type="text" name='message_type'>
				</td>
			</tr>
												
			<tr>
				<td>message</td>
				<td><textarea rows="5" cols="20" name='message'></textarea>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='informall'
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