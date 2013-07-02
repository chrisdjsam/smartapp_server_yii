<?php include_once 'common_header.php';?>
	<form action="<?php echo($baseURL)?>robot.post_map_data" method='POST'
		id='robotpostMap' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Post robot map data" colspan="2"><label>Post robot map data.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to post robot map data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.post_map_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot</li>
							<li><b>xml_data</b> :XML data for robot map (Optional)</li>
							<li><b>blob_data</b> :Blob data for robot map (Optional)</li>
							<!-- 							<li><b>blob_data_file_extension</b> :blob_data_file_extension (Optional)</li> -->
							<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
							</li>
						</ul>
						Scenarios
						<ul>
							<li>If xml_data is not provided
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
										{"status":0,"result":{"success":true,"robot_map_id":"5","xml_data_version":1,"blob_data_version":1}}
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
							<li>If serial no is not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Serial number does not exist", "error":{"code":-114, "message":"Serial number does not exist"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a serial number is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.post_map_data","error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td><input type="button" name='submit' dummy='robotpostMap'
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

	<form action="<?php echo($baseURL)?>robot.get_maps" method='POST'
		id='robotGetmaps' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Get Robot Maps" colspan="2"><label>Get Robot Maps</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot maps. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_maps<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot</li>
						</ul>
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":[{"id":"1","xml_data_version":"2","blob_data_version":"1"},{"id":"2","xml_data_version":"3","blob_data_version":"1"}]}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and map does not exist
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
							<li>If serial number is not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":""Serial number does not exist"", "error":{"code":-114, "message":""Serial number does not exist""}}
                                                                        </li>
								</ul>
							</li>
                                                        <li>If serial number is missing.
                                                                 <ul>
                                                                        <li>
                                                                            {"status":-1,"message":"Missing parameter serial_number in method robot.get_maps","error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td><input type="button" name='submit' dummy='robotGetmaps'
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

	<form action="<?php echo($baseURL)?>robot.get_map_data" method='POST'
		id='robotGetmapdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id ="Get Robot Map Data" colspan="2"><label>Get Robot Map Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot map data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_map_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_map_id</b> :Robot Map Id</li>
						</ul>
						Success Responses:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353061075.xml","blob_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/blob\/Koala.jpg"}}
									</li>
								</ul>
							</li>
							<li>If everything goes fine and blob data file is not exist
								<ul>
									<li>
										{"status":0,"result":{"xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353397443.xml","blob_data_url":""}}
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
							<li>If robot map id is not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot map id does not exist", "error":{"code":-127, "message":"Robot map id does not exist."}}
                                                                        </li>
								</ul>
							</li>
							<li>If a parameter(robot_map_id) is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter robot_map_id in method robot.get_map_data", "error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>robot_map_id</td>
				<td><input type="text" name='robot_map_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotGetmapdata'
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

	<form action="<?php echo($baseURL)?>robot.update_map_data"
		method='POST' id='robot_update_map_data' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Update robot map data" colspan="2"><label>Update robot map data.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to update robot map data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.update_map_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>map_id</b> :Robot Map Id</li>
							<li><b>xml_data_version</b> :XML data version</li>
							<li><b>xml_data</b> :XML data for robot map</li>
							<li><b>blob_data_version</b> :Blob data version</li>
							<li><b>blob_data</b> :Blob data for robot map</li>
							<!-- 							<li><b>blob_data_file_extension</b> :blob_data_file_extension (Optional)</li> -->
							<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
							</li>
						</ul>
						Scenarios
						<ul>
							<li>If xml data version provided and xml data field is blank
								<ul>
									<li>It would update previous xml data with blank data</li>
								</ul>
							</li>
						</ul>
<!-- 						<ul> -->
<!-- 							<li>If blob data version provided and blob data file not provided -->
<!-- 								<ul> -->
<!-- 									<li>It would delete previous blob data file</li> -->
<!-- 								</ul> -->
<!-- 							</li> -->
<!-- 						</ul> -->
						<ul>
							<li>If blob data version provided and both blob_data and encoded_blob_data are not provided
								<ul>
									<li>It would delete previous blob data file</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and only blob_data is provided
								<ul>
									<li>It would delete previous blob data file and create blob file with provided blob_data file.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and only encoded_blob_data is provided
								<ul>
									<li>It would delete previous blob data file and create blob file with provided encoded_blob_data.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and both blob_data and encoded_blob_data are provided
								<ul>
									<li>It would delete previous blob data file and create blob file with provided encoded_blob_data.</li>
								</ul>
							</li>
						</ul>
						<ul>
							<li>If blob data version provided and encoded_blob_data is provided
								<ul>
									<li>It would delete previous blob data file and blob data check for file mime type,
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
									<li>It would delete previous blob data file and create blob file with provided blob_data file extension.</li>
								</ul>
							</li>
						</ul>
						
						Success Responses:
						<ul>
							<li>If xml data version provided and goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot map data."}}</li>
								</ul>
							</li>
							<li>If blob data version provided and goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot map data."}}</li>
								</ul>
							</li>
							<li>If both xml and blob data version provided,everything goes
								fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully updated robot map data."}}</li>
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
							<li>If robot map id is not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot map id does not exist", "error":{"code":-127, "message":"Robot map id does not exist."}}
                                                                        </li>
								</ul>
							</li>
							<li>If both the data versions are missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Provide at least one data version(xml or blob).", "error":{"code":"-128","message":"Provide at least one data version(xml or blob) or schedule type."}}
                                                                        </li>
								</ul>
							</li>
							<li>If xml data version is provided, not matching with latest xml data version
								<ul>
									<li>
                                                                            {"status":-1,"message":"Version mismatch for xml data.", "error":{"code":-129, "message":"Version mismatch for xml data."}}
                                                                        </li>
								</ul>
							</li>
							<li>If blob data version is provided, not matching with latest blob data version
								<ul>
									<li>
                                                                            {"status":-1,"message":"Version mismatch for blob data.", "error":{"code":-130, "message":"Version mismatch for blob data"}}
                                                                        </li>
								</ul>
							</li>
							<li>If a parameter(robot_map_id) is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter robot_map_id in method robot.update_map_data", "error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>map_id</td>
				<td><input type="text" name='map_id'>
				</td>
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
				<td><input type="button" name='submit' dummy='robot_update_map_data'
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

	<form action="<?php echo($baseURL)?>robot.delete_map" method='POST'
		id='robotDeletemapdata' class='ajaxified_forms'>

		<table class='custom_table'>
			<tr>
				<td id = "Delete Robot Map" colspan="2"><label>Delete Robot Map</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete robot map. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.delete_map<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>robot_map_id</b> :Robot Map Id</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>{"status":0,"result":{"success":true,"message":"You have
										successfully deleted robot map data."}}</li>
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
							<li>If robot map id is not exist
								<ul>
									<li>
                                                                            {"status":-1,"message":"Robot map id does not exist", "error":{"code":-127, "message":"Robot map id does not exist."}}
                                                                        </li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>
                                                                            {"status":-1,"message":"Missing parameter robot_map_id in method robot.delete_map", "error":{"code":"-102","message":"Missing parameter in method call"}}
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
				<td>robot_map_id</td>
				<td><input type="text" name='robot_map_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotDeletemapdata'
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