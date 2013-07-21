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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If serial_number not found:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114, "message":"Robot serial number does not exist"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If user_id not found:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User ID does not exist", "error":{"code":"-119","message":"User ID does not exist"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If user and robot association not exist:
								<ul>
									<li>
                                                                            {"status":-1,"message":"User robot association does not exist.", "error":{"code":"-120","message":"User robot association does not exist."}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If message sending failed:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Message could not be sent to robot sr1.", "error":{"code":-121, "message":"Message could not be sent to robot "}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If user id is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter user_id in method message.send_xmpp_message_to_robot","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If serial_number is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Message could not be sent to robot sr1.", "error":{"code":-121, "message":"Message could not be sent to robot "}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If message sending failed:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method message.send_xmpp_message_to_robot","error":{"code":"-102","message":"Missing parameter in method call"}}
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If sending failed:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Message is sent to 0 user(s).", "error":{"code":"-121","message":"Message could not be sent to robot"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If serial_nubmer not found:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114, "message":"Robot serial number does not exist"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If message_type does not match:
								<ul>
									<li>
                                                                            {"status":-1,"message":"XMP does not match supported message type XMPP", "error":{"code":"-122","message":"Does not match supported message type"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If message_type is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter message_type in method message.send_message_to_associated_users","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                         <li>If serial_number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method message.send_message_to_associated_users","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                         <li>If message is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter message in method message.send_message_to_associated_users","error":{"code":"-102","message":"Missing parameter in method call"}}
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


	<form action="<?php echo($baseURL)?>message.send_xmpp_message_to_all_associated_users2" method='POST'
		id='sendXMPPMessageToAllAssociatedUsers2' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Send XMPP Message To All Associated Users 2" colspan="2"><label>Send XMPP Message To All Associated Users 2</label>
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
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot sending robot.</li>
							<li><b>only_online</b> :Enter '1' to send notification to only online users.</li>
							<li><b>message</b> :Message Text.</li>
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
									<li>
                                                                            {"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication failed"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If serial_nubmer not found:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method message.send_xmpp_message_to_all_associated_users2","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>if message is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter message in method message.send_xmpp_message_to_all_associated_users2","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If Robot serial number does not exist"
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
				<td>serial_number</td>
				<td><input type="text" name='serial_number'>
				</td>
			</tr>
							
                        <tr>
				<td>only_online</td>
				<td>
                                    <input type="text" name='only_online'>
                                    <span style="color:blue">(Enter '1' to set this flag)</span>
				</td>
			</tr>
                        
			<tr>
				<td>message</td>
				<td><textarea rows="5" cols="20" name='message'></textarea>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='sendXMPPMessageToAllAssociatedUsers2'
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


        <form action="<?php echo($baseURL)?>message.send_notification_to_given_emails" method='POST'
		id='notification_to_all_by_emails' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Send notification to given emails" colspan="2"><label>Send notification to given emails.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to send notification to one or more given emails. <br /> <br /> URL:
						<?php echo($baseURL)?>
						message.send_notification_to_given_emails<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> 		: Your API Key</li>
							<li><b>emails</b>               : Emails to send notification.</li>
							<li><b>message</b> 		: Message Text.</li>
						</ul>
				

						Success Response:
						<ul>
							<li>If notification is sent to given emails
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":"Notification sent to registration ids : [\"APA91bHYu4xx4LK2gY_JHN-7546z7VrWRxv0m2NgxXRtgR6m0Jrdr_qaHOfF0-v5-5hyxRWyqN0Vg8xR40eQhHcMvmuAcDdRbmkiarKVdX_hVl0XA0GN66ndm_wsEjY6_KRIjvP_ec0-tkNvNkbZQfudoyuKsWOz9g\"]"}}
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
                                                        
                                                        <li>If emails is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please provide at least one email address", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If message is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Message field can not be blank", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If there no registration id for given email ids
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please register notification for given emails ( Causing Emails : < list of all unregistered emails > )", "error":{"code":"-121","message":"Message could not be sent to robot"}}
                                                                        </li>
								</ul>
							</li>
							
                                                        <li>If provide email address is not present in database
								<ul>
									<li>
                                                                            {"status":-1,"message":"Provided emails addresses are not exist in our system ( Causing Emails : < list of all unavailable emails > ))", "error":{"code":"-121","message":"Message could not be sent to robot"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                          <li>If email is invalid
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please provide valid email address (Invalid emails: [])","error":{"code":"-105","message":"The email address you provided does not appear to be a valid email address."}}
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

			<tr id="append_given_email">
                                <td>emails</td>
                                <td>
                                    <input type="text" name='emails[]' id="given_email">
                                    <span id='loadEmails'>Add More</span>
                                </td>
			</tr>
                        
			<tr>
				<td>message</td>
				<td><textarea rows="5" cols="30" name='message'></textarea>
				</td>
			</tr>
                        
			
			<tr>
				<td><input type="button" name='submit' dummy='notification_to_all_by_emails'
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


        <form action="<?php echo($baseURL)?>message.send_notification_to_given_registration_ids" method='POST'
		id='notification_to_all' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Send notification to given Registration IDs" colspan="2"><label>Send notification to given Registration IDs.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to send notification to one or more given registration ids. <br /> <br /> URL:
						<?php echo($baseURL)?>
						message.send_notification_to_given_registration_ids<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> 		: Your API Key</li>
							<li><b>registration_ids</b>     : Registration Ids to send notification.</li>
							<li><b>message</b> 		: Message Text.</li>
						</ul>
				

						Success Response:
						<ul>
							<li>If notification is sent to given registration ids
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":" gcm_response::{\"multicast_id\":5064984455803641243,\"success\":1,\"failure\":0,\"canonical_ids\":0,\"results\":[{\"message_id\":\"0:1365746660503409%d7e43d19f9fd7ecd\"}]}"}}
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
                                                        
                                                        <li>If registration id is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Provide at least one registration id", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If message is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter message in method message.send_notification_to_given_registration_ids","error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If given registration ids are not registered:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Provided Registration Ids are not registered, please register it first then try again... (Causing Registration Ids : < list of all unregistered ids >)", error":{"code":"-125","message":"Invalid registration IDs"}}
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

			<tr id="append_given_registration_id">
                                <td>registration_ids</td>
                                <td>
                                    <input type="text" name='registration_ids[]' id="given_registration_id">
                                    <span id='loadRegistrationIds'>Add More</span>
                                </td>
			</tr>
                        
			<tr>
				<td>message</td>
				<td><textarea rows="5" cols="30" name='message'></textarea>
				</td>
			</tr>
                        
			
			<tr>
				<td><input type="button" name='submit' dummy='notification_to_all'
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


        <form action="<?php echo($baseURL) ?>message.send_notification_to_all_users_of_robot" method='POST'
              id='notification_to_all_associated_users' class='ajaxified_forms'
              enctype="multipart/form-data">

            <table class='custom_table'>
                <tr>
                    <td id = "Send notification to all the users of robot" colspan="2"><label>Send notification to all the users of robot.</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='api_description'>

                        <div class='toggle_details'>More</div>
                        <div class='details_div'>
                            POST method to send notification to all users of robot. <br /> <br /> URL:
                            <?php echo($baseURL) ?>
                            message.send_notification_to_all_users_of_robot<br /> 
                            Parameters:
                            <ul>
                                <li><b>api_key</b> 		: Your API Key</li>
                                <li><b>serial_number</b>        : Serial Number of Robot.</li>
                                <li><b>message</b> 		: Message Text.</li>
                            </ul>


                            Success Response:
                            <ul>
                                <li>If notification is sent to all the users of robot.
                                    <ul>
                                        <li>
                                            {"status":0,"result":{"success":true}}
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

                                <li>If serial_number is missing:
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"Missing parameter serial_number in method message.send_notification_to_all_users_of_robot", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                        </li>
                                    </ul>
                                </li>

                                <li>If message is missing:
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"Missing parameter message in method message.send_notification_to_given_registration_ids", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                        </li>
                                    </ul>
                                </li>

                                <li>If there is not single user with notification registration:
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"Sorry, there is not single user who is registered for notification","error":{"code":"-121","message":"Message could not be sent to robot"}}
                                        </li>
                                    </ul>
                                </li>

                                <li>If Robot serial number is incorrect:
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"Robot serial number does not exist", "error":{"code":-114, "message":"Robot serial number does not exist"}}
                                        </li>
                                    </ul>
                                </li>
                                
                                <li>If no user is associated
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"Sorry, there is no user associated","error":{"code":"-123","message":"No user was found for robot"}}
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
                                                   class='api_keys' value='<?php echo($api_key); ?>' />
                    </td>
                </tr>

                <tr>
                    <td>serial_number</td>
                    <td><input type="text" name='serial_number'>
                    </td>
                </tr>

                <tr>
                    <td>message</td>
                    <td><textarea rows="5" cols="30" name='message'></textarea>
                    </td>
                </tr>


                <tr>
                    <td><input type="button" name='submit' dummy='notification_to_all_associated_users'
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

        <form action="<?php echo($baseURL) ?>message.send_notification_to_all_users_of_robot2" method='POST'
              id='notification_to_all_associated_users2' class='ajaxified_forms'
              enctype="multipart/form-data">

            <table class='custom_table'>
                <tr>
                    <td id = "Send notification to all the users of robot2" colspan="2"><label>Send notification to all the users of robot2.</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='api_description'>

                        <div class='toggle_details'>More</div>
                        <div class='details_div'>
                            POST method to send notification to all users of robot2. <br /> <br /> URL:
                            <?php echo($baseURL) ?>
                            message.send_notification_to_all_users_of_robot2<br /> 
                            Parameters:
                            <ul>
                                <li><b>api_key</b> 		: Your API Key</li>
                                <li><b>serial_number</b>        : Serial Number of Robot.</li>
                                <li><b>message</b> 		: JSON object in '{"notifications":[{"id":"101"}]}' format.</li>
                            </ul>


                            Success Response:
                            <ul>
                                <li>If notification is sent to at least one user of robot.
                                    <ul>
                                        <li>
                                            {"status":0,"result":{"success":true}}
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

                                <li>If serial_number is missing:
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"Missing parameter serial_number in method message.send_notification_to_all_users_of_robot", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                        </li>
                                    </ul>
                                </li>

                                <li>If message is missing:
                                    <ul>
                                        <li>
                                             {"status":-1,"message":"Missing parameter message in method message.send_notification_to_given_registration_ids", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                        </li>
                                    </ul>
                                </li>

                                <li>If Json message is invalid
                                    <ul>
                                        <li>
                                            {"status":-1,"message":"The json message you have provided does not appear to be a valid.","error":{"code":"-126","message":"The JSON Object you have provided does not appear to be a valid."}}
                                        </li>
                                    </ul>
                                </li>

                                <li>If enter wrong serial_number
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
                                                   class='api_keys' value='<?php echo($api_key); ?>' />
                    </td>
                </tr>

                <tr>
                    <td>serial_number</td>
                    <td><input type="text" name='serial_number'>
                    </td>
                </tr>

                <tr>
                    <td>message</td>
                    <td><textarea rows="5" cols="30" name='message'></textarea>
                    </td>
                </tr>


                <tr>
                    <td><input type="button" name='submit' dummy='notification_to_all_associated_users2'
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

        <form action="<?php echo($baseURL)?>message.notification_registration" method='POST'
		id='notification_registration' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Store Registration Id To Send Notification For Given User" colspan="2"><label>Register For Notification</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to register for notification. <br /> <br /> URL:
						<?php echo($baseURL)?>
						message.notification_registration<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b>         :Your API Key</li>
							<li><b>user_email</b>      :User's email for whom you are storing registration id.</li>
							<li><b>registration_id</b> :Registration Id</li>
							<li><b>device_type</b>     :Device Type ( Consideration: 1 for 'Android Device', 2 for 'iPhone Device' ).</li>
						
						</ul>
				

						Success Response:
						<ul>
							<li>If registration id stored
								<ul>
									<li>
										"status":0,"result":{"success":true,"message":"Registered successfully"}}
									</li>
								</ul>
							</li>
                                                        <li>If Provide same registration id with different data
								<ul>
									<li>
										"status":0,"result":{"success":true,"message":"Notification registration details updated successfully"}}
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
							
							<li>If user's email not found:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided user email address does not exist in our system.", "error":{"code":"-112","message":"Email does not exist."}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If provide invalid email address:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Please enter valid email address.", "error":{"code":"-105","message":"The email address you provided does not appear to be a valid email address."}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If user's email is missing
                                                                <ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter user_email in method message.notification_registration","error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>user_email</td>
				<td><input type="text" name='user_email'></td>
			</tr>
			<tr>
				<td>registration_id</td>
				<td><input type="text" name='registration_id'> </td>
			</tr>
												
			<tr>
				<td>device_type</td>
				<td>
                                    <input type="text" name='device_type'>
                                    <span style="color: blue;">( Consideration: 1 for 'Android Device', 2 for 'iPhone Device' )</span>
                                </td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='notification_registration' value='Submit' class='submit_form'></td>
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
	
	
        <form action="<?php echo($baseURL)?>message.notification_unregistration" method='POST'
		id='notification_unregistration' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Remove Notification Registration Id" colspan="2"><label>Unregister From Notification</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to unregister from notification. <br /> <br /> URL:
						<?php echo($baseURL)?>
						message.notification_unregistration<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b>         :Your API Key</li>
							<li><b>registration_id</b> :Registration Id</li>
						</ul>
				

						Success Response:
						<ul>
                                                        <li>If registration id unregistered
								<ul>
									<li>
										"status":0,"result":{"success":true,"message":"Unregistered successfully"}}
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
							
							<li>If registration is not found:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, Provided registration id does not exist in our system", "error":{"code":"-125","message":"Invalid registration IDs"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If registration id field is blank:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter registration_id in method message.notification_unregistration", "error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>registration_id</td>
				<td><input type="text" name='registration_id'> </td>
			</tr>
												
			<tr>
				<td><input type="button" name='submit' dummy='notification_unregistration' value='Submit' class='submit_form'></td>
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


        <form action="<?php echo($baseURL)?>message.set_user_push_notification_options" method='POST'
		id='set_user_push_notification_options' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Set User Push Notification Options" colspan="2"><label>Set User Push Notification Options</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to Set User Push Notification Options. <br /> <br /> URL:
						<?php echo($baseURL)?>
						message.set_user_push_notification_options<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> : Your API Key</li>
							<li><b>email</b> : email address of user</li>
                                                        <li><b>json_object</b> : JSON Ojbect in '{"global":"true", "notifications":[{"key":"101", "value":"true"}, {"key":"102", "value":"true"}, {"key":"103", "value":"true"}]}' format</li>
						</ul>
				

						Success Response:
						<ul>
                                                        <li>Web service response when user enters push notification preference first time,
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":"Successfully saved user push notification preferences."}}
									</li>
								</ul>
							</li>
                                                        <li>Web service response when user try to set push notification preference again,
								<ul>
									<li>
										{"status":0,"result":{"success":true,"message":"Successfully updated user push notification preferences."}}
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
							
							<li>If email address is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter email in method message.set_user_push_notification_options", "error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							
							<li>If json_object is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter json_object in method message.set_user_push_notification_options", "error":{"code":"-102","message":"Missing parameter in method call"}}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If given email address is not valid,
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided does not appear to be a valid email address.", "error":{"code":-105, "message":"The email address you have provided does not appear to be a valid email address"}}
                                                                        </li>
								</ul>
							</li>                                                        
                                                        
                                                        <li>If given email address does not exist in database,
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, email address that you provided does not exist our database", "error":{"code":-112, "message":"Email does not exist."}}
                                                                        </li>
								</ul>
							</li>
														
                                                        <li>If given json_object is not valid,
								<ul>
									<li>
                                                                            {"status":-1,"message":"The JSON Object you have provided does not appear to be a valid.", "error":{"code":"-126","message":"The JSON Object you have provided does not appear to be a valid."}}
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
				<td><input type="text" name='email'> </td>
			</tr>
                        
			<tr>
				<td>json_object</td>
				<td><input type="text" name='json_object'> </td>
			</tr>									
                        
			<tr>
				<td><input type="button" name='submit' dummy='set_user_push_notification_options' value='Submit' class='submit_form'></td>
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


        <form action="<?php echo($baseURL)?>message.get_user_push_notification_options" method='POST'
		id='get_user_push_notification_options' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Get User Push Notification Options" colspan="2"><label>Get User Push Notification Options</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
				
				<div class='toggle_details'>More</div>
					<div class='details_div'>
						POST method to get user push notification options. <br /> <br /> URL:
						<?php echo($baseURL)?>
						message.get_user_push_notification_options<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> : Your API Key</li>
							<li><b>email</b> : email address of user</li>
						</ul>
				

						Success Response:
						<ul>
                                                        <li>If user enters valid email,
								<ul>
									<li>
										{"status":0,"result":{"global":true,"notifications":[{"key":"101","value":true},{"key":"102","value":true},{"key":"103","value":true}]}}
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
							
							<li>If email is missing:
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter email in method message.get_user_push_notification_options", error":{"code":"-102","message":"Missing parameter in method call"}}
                                                                        </li>
								</ul>
							</li>
							
                                                        <li>If given email address is not valid,
								<ul>
									<li>
                                                                            {"status":-1,"message":"The email address you have provided does not appear to be a valid email address.", error":{"code":"-105","message":"The email address you provided does not appear to be a valid email address."}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If given email address does not exist in database,
								<ul>
									<li>
                                                                            {"status":-1,"message":"Sorry, email address that you provided does not exist our database", "error":{"code":-112, "message":"Email does not exist."}}
                                                                        </li>
								</ul>
							</li>
                                                        
                                                        <li>If push notification preferences for the email address
                                                                <ul>
									<li>
                                                                            {"status":-1,"message":"Please first set user push notification preferences for the email address that you have provided and then try again.","error":{"code":"-124","message":"User preference does not permit this operation."}}
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
				<td><input type="text" name='email'> </td>
			</tr>
                        
			<tr>
				<td><input type="button" name='submit' dummy='get_user_push_notification_options' value='Submit' class='submit_form'></td>
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