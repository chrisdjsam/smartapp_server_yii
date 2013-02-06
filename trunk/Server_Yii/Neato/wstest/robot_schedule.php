<?php include_once 'common_header.php';?>
	<form action="<?php echo($baseURL)?>robotschedule.post_data"
		method='POST' id='robotschedulePostdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Post Robot Schedule Data" colspan="2"><label>Post Robot Schedule Data.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to post robot schedule data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robotschedule.post_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial Number of robot</li>
							<li><b>schedule_type</b> :Basic OR Advanced</li>
							<li><b>xml_data</b> :XML data for robot schedule (Optional)</li>
							<!-- 							<li><b>blob_data_file_extension</b> :blob_data_file_extension (Optional)</li> -->
							<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
							</li>
							<li><b>blob_data</b> :Blob data for robot schedule (Optional)</li>
						</ul>
						Scenarios
						<ul>
							<li>If xml data is not provided
								<ul>
									<li>It would create xml file with blank data</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If both blob_data and encoded_blob_data are not provided
								<ul>
									<li>It would not create blob file.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If only blob_data is provided
								<ul>
									<li>It would create blob file with provided blob_data file.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If only encoded_blob_data is provided
								<ul>
									<li>It would create blob file with provided encoded_blob_data.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If both blob_data and encoded_blob_data are provided
								<ul>
									<li>It would create blob file with provided encoded_blob_data.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If encoded_blob_data is provided
								<ul>
									<li>Blob data check for file mime type,
										<ul>
											<li>if file mime type is image it will check for file extension (jpg/jpeg/gif/png)</li>
											<li>if file mime type is other than image it will store file with default extension <b>jpg</b></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob_data is provided
								<ul>
									<li>It would create blob file with provided blob_data file extension.</li>
								</ul>
							</li>
						</ul>
						
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"success":true,"robot_schedule_id":"5","schedule_type":"Advanced","xml_data_version":1,"blob_data_version":1}}
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
							<li>If serial no is not exist
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
								</ul>
							</li>
							<li>If a serial number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robotschedule.post_data"}</li>
								</ul>
							</li>
							<li>If schdule type not valid or missing
								<ul>
									<li>{"status":-1,"message":"Robot schedule type is not valid"}</li>
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
				<td>schedule_type</td>
				<td><select name='schedule_type' class='schedule_type_select'>
						<option value="Basic" selected="selected">Basic</option>
						<option value="Advanced">Advanced</option>
				</select></td>
			</tr>
			<tr>
				<td>xml_data</td>
				<td><textarea rows="5" cols="20" name='xml_data'></textarea>
				</td>
			</tr>
			<!-- 			<tr> -->
			<!-- 				<td>blob_data_file_extension</td> -->
			<!-- 				<td><input type="text" name='blob_data_file_extension'> -->
			<!-- 				</td> -->
			<!-- 			</tr> -->
			<tr>
				<td>encoded_blob_data</td>
				<td><textarea rows="5" cols="20" name='encoded_blob_data'></textarea>
				</td>
			</tr>
			<tr>
				<td>blob_data</td>
				<td><input type="file" name='blob_data'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotschedulePostdata'
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

	<form action="<?php echo($baseURL)?>robotschedule.get_schedules"
		method='POST' id='robotscheduleGetschedules' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Get Robot Schedules" colspan="2"><label>Get Robot Schedules</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot schedules. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robotschedule.get_schedules<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot</li>
						</ul>
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":[{"id":"1","schedule_type":"Advanced","xml_data_version":"1","blob_data_version":"1"},{"id":"6","schedule_type":"Basic","xml_data_version":"1","blob_data_version":"1"}]}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and schedule does not exist
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
							<li>If serial number is not exist
								<ul>
									<li>{"status":-1,"message":""Serial number does not exist""}</li>
								</ul>
							</li>
							<li>If a serial number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robotschedule.get_schedules"}</li>
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
				<td><input type="button" name='submit'
					dummy='robotscheduleGetschedules' value='Submit'
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


	<form action="<?php echo($baseURL)?>robotschedule.get_data"
		method='POST' id='robotscheduleGetdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Get Robot Schedule Data" colspan="2"><label>Get Robot Schedule Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot schedule data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robotschedule.get_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_schedule_id</b> :Robot Schedule Id</li>
						</ul>
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"schedule_type":"Basic","xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353061075.xml","blob_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/blob\/Koala.jpg"}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and blob data file is not exist
								<ul>
									<li>
										{"status":0,"result":{"schedule_type":"Advanced","xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353397443.xml","blob_data_url":""}}
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
							<li>If robot schedule id is not exist
								<ul>
									<li>{"status":-1,"message":"Robot schedule id does not exist"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_schedule_id
										in method robotschedule.get_data"}</li>
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
				<td>robot_schedule_id</td>
				<td><input type="text" name='robot_schedule_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotscheduleGetdata'
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

	<form action="<?php echo($baseURL)?>robotschedule.update_data"
		method='POST' id='robotscheduleUpdatedata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Update Robot Schedule Data" colspan="2"><label>Update Robot Schedule Data.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to update robot schedule data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robotschedule.update_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_schedule_id</b> :Robot Schedule Id</li>
							<li><b>schedule_type</b> :Basic OR Advanced (Optional)</li>
							<li><b>xml_data_version</b> :XML data version</li>
							<li><b>xml_data</b> :XML data for robot schedule (Optional)</li>
							<li><b>blob_data_version</b> :Blob data version</li>
							<!-- 							<li><b>blob_data_file_extension</b> :blob_data_file_extension (Optional)</li> -->
							<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
							</li>
							<li><b>blob_data</b> :Blob data for robot schedule (Optional)</li>
						</ul>
						Scenarios
						<ul>
							<li>If xml data version provided and xml data field is blank
								<ul>
									<li>It would update previous xml data with blank data</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and both blob_data and encoded_blob_data are not provided
								<ul>
									<li>It would not create blob file.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and only blob_data is provided
								<ul>
									<li>It would create blob file with provided blob_data file.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and only encoded_blob_data is provided
								<ul>
									<li>It would create blob file with provided encoded_blob_data.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and both blob_data and encoded_blob_data are provided
								<ul>
									<li>It would create blob file with provided encoded_blob_data.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and encoded_blob_data is provided
								<ul>
									<li>Blob data check for file mime type,
										<ul>
											<li>if file mime type is image it will check for file extension (jpg/jpeg/gif/png)</li>
											<li>if file mime type is other than image it will store file with default extension <b>jpg</b></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and blob_data is provided
								<ul>
									<li>It would create blob file with provided blob_data file extension.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If xml data version and blob data version are provided but
								schedule type is same as previous schedule type
								<ul>
									<li>It would not change anything and you will get message as
										"Robot schedule data is upto date"</li>
								</ul>
							</li>
						</ul>
						Success Responses:
						<ul>
							<li>If xml data version provided and goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot schedule data."}}</li>
								</ul>
							</li>
							<li>If blob data version provided and goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot schedule data."}}</li>
								</ul>
							</li>
							<li>If both xml and blob data version provided,everything goes
								fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot schedule data."}}</li>
								</ul>
							</li>
						</ul>

						Failure Response: <br />
						<ul>
							<li>If API Key is missing:
								<ul>
									<li>{"status":-1,"message":"Method call failed the API
										Authentication"}</li>
								</ul>
							</li>
							<li>If robot schedule id is not exist
								<ul>
									<li>{"status":-1,"message":"Robot schedule id does not exist"}</li>
								</ul>
							</li>
							<li>If parameter robot schedule id is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_schedule_id
										in method robotschedule.update_data"}</li>
								</ul>
							</li>
							<li>If schdule type not valid or missing
								<ul>
									<li>{"status":-1,"message":"Robot schedule type is not valid"}</li>
								</ul>
							</li>
							<li>If both the data versions are missing
								<ul>
									<li>{"status":-1,"message":"Provide at least one data
										version(xml or blob) or schedule type."}</li>
								</ul>
							</li>
							<li>If xml data version is provided, not matching with latest xml
								data version
								<ul>
									<li>{"status":-1,"message":"Version mismatch for xml data."}</li>
								</ul>
							</li>
							<li>If blob data version is provided, not matching with latest
								blob data version
								<ul>
									<li>{"status":-1,"message":"Version mismatch for blob data."}</li>
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
				<td>robot_schedule_id</td>
				<td><input type="text" name='robot_schedule_id'>
				</td>
			</tr>
			<tr>
				<td>schedule_type</td>
				<td><select name='schedule_type' class='schedule_type_select'>
						<option value="" selected="selected"></option>
						<option value="Basic">Basic</option>
						<option value="Advanced">Advanced</option>
				</select></td>
			</tr>
			<tr>
				<td>xml_data_version</td>
				<td><input type="text" name='xml_data_version'>
				</td>
			</tr>
			<tr>
				<td>xml_data</td>
				<td><textarea rows="5" cols="20" name='xml_data'></textarea>
				</td>
			</tr>
			<tr>
				<td>blob_data_version</td>
				<td><input type="text" name='blob_data_version'>
				</td>
			</tr>
			<!-- 			<tr> -->
			<!-- 				<td>blob_data_file_extension</td> -->
			<!-- 				<td><input type="text" name='blob_data_file_extension'> -->
			<!-- 				</td> -->
			<!-- 			</tr> -->
			<tr>
				<td>encoded_blob_data</td>
				<td><textarea rows="5" cols="20" name='encoded_blob_data'></textarea>
				</td>
			</tr>
			<tr>
				<td>blob_data</td>
				<td><input type="file" name='blob_data'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit'
					dummy='robotscheduleUpdatedata' value='Submit' class='submit_form'>
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
	
	<form action="<?php echo($baseURL)?>robotschedule.delete_data" method='POST'
		id='robotDeletescheduledata' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Delete Robot Schedule Data" colspan="2"><label>Delete Robot Schedule Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete robot Schedule. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robotschedule.delete_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_schedule_id</b> :Robot Schedule Id</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully deleted robot schedule data."}}</li>
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
							<li>If robot schedule id is not exist
								<ul>
									<li>{"status":-1,"message":"Robot schedule id does not exist"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_schedule_id in method robotschedule.delete_data"}</li>
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
				<td>robot_schedule_id</td>
				<td><input type="text" name='robot_schedule_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotDeletescheduledata'
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