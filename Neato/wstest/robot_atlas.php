<?php include_once 'common_header.php';?>
	<form action="<?php echo($baseURL)?>robot.add_atlas" method='POST'
		id='robotaddAtlas' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Add robot atlas" colspan="2"><label>Add Robot Atlas.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to add robot atlas data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.add_atlas<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot</li>
							<li><b>xml_data</b> :XML data for robot atlas</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"success":true,"robot_atlas_id":"61","xml_data_version":1,"message":"You have successfully added Robot Atlas"}}
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
							<li>If serial number does not exists
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
								</ul>
							</li>
							<li>If a serial number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.add_atlas"}</li>
								</ul>
							</li>
							<li>If XML data is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter xml_data in
										method robot.add_atlas"}</li>
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
			<tr>
				<td><input type="button" name='submit' dummy='robotaddAtlas'
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

	<form action="<?php echo($baseURL)?>robot.get_atlas_data" method='POST'
		id='robotGetatlasdata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Get Robot Atlas Data" colspan="2"><label>Get Robot Atlas Data</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to get robot atlas. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_atlas_data<br /> Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":{"atlas_id":"32","xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_atlas_data\/32\/xml\/1357653845.xml","version":"1"}}
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
							<li>If robot serial_number does not exist
								<ul>
									<li>{"status":-1,"message":"Robot serial_number does not exist."}</li>
								</ul>
							</li>
							<li>If robot atlas id does not exist
								<ul>
									<li>{"status":-1,"message":"Robot atlas does not exist for this robot"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.get_atlas_data"}</li>
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
				<td><input type="button" name='submit' dummy='robotGetatlasdata'
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


	<form action="<?php echo($baseURL)?>robot.update_atlas" method='POST'
		id='robotupdateAtlas' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Update or add robot atlas data" colspan="2"><label>Update Or Add Robot Atlas Data.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to update or add robot atlas data. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.update_atlas<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>serial_number</b> :Serial number of robot, required if new atlas is to be added. Else ignored.</li>
							<li><b>atlas_id</b> : Robot Atlas Id. If 0 is passed, a new atlas is added.</li>
							<li><b>delete_grids</b> : If 1 is passed, all the grids related to this atlas are deleted. Else this parameter is ignored.</li>
							<li><b>xml_data_version</b> :XML data version. pass 0 in case of new atlas.</li>
							<li><b>xml_data</b> :XML data for robot atlas</li>
														
						</ul>
						Success Responses:
						<ul>
						
							<li>If serial number provided and atals id passed as 0 (add new):
								<ul>
									<li>
										{"status":0,"result":"{\"success\":true,\"robot_atlas_id\":\"29\",\"xml_data_version\":1}"}
									</li>
								</ul>
							</li>
						
							<li>If xml data is provided and existing atals id passed :
								<ul>
									<li>{"status":0,"result":"{\"success\":true,\"message\":\"You have successfully updated robot atlas data.\"}"}</li>
								</ul>
							</li>
							
							<li>If existing atals id passed and delete_grids is passed as 1 (true)  :
								<ul>
									<li>{"status":0,"result":{"success":true,"robot_atlas_id":"58","xml_data_version":7,"message":"You have successfully deleted 2 grids, You have successfully updated atlas data."}}</li>
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
							<li>If robot atlas id does not exist
								<ul>
									<li>{"status":-1,"message":"Robot atlas id does not exist"}</li>
								</ul>
							</li>
							<li>If xml data version is provided, not matching with latest xml
								data version
								<ul>
									<li>{"status":-1,"message":"Version mismatch for xml data."}</li>
								</ul>
							</li>
							<li>If xml data version is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter xml_data_version
										in method robot.update_atlas"}</li>
								</ul>
							</li>
							<li>If XML data is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter xml_data in
										method robot.update_atlas"}</li>
								</ul>
							</li>
							<li>If atlas_id parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter atlas_id in
										method robot.update_atlas"}</li>
								</ul>
							</li>
							
							<li>If serial number does not exists
								<ul>
									<li>{"status":-1,"message":"Serial number does not exist"}</li>
								</ul>
							</li>
							<li>If a serial number is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter serial_number in
										method robot.add_atlas"}</li>
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
				<td>atlas_id</td>
				<td><input type="text" name='atlas_id'>
				</td>
			</tr>
			<tr>
				<td>delete_grids</td>
				<td><input type="text" name='delete_grids'>
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
				<td><input type="button" name='submit' dummy='robotupdateAtlas'
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

	<form action="<?php echo($baseURL)?>robot.delete_atlas" method='POST'
		id='robotDeleteAtlas' class='ajaxified_forms'
		enctype="multipart/form-data">
		<table class='custom_table'>
			<tr>
				<td id = "Delete Robot Atlas" colspan="2"><label>Delete Robot Atlas</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete robot atlas. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.delete_atlas<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>atlas_id</b> :Robot Atlas Id</li>
						</ul>
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":"{\"success\":true,\"message\":\"You have successfully deleted robot atlas.\"}"}
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
							<li>If robot atlas id does not exist
								<ul>
									<li>{"status":-1,"message":"Robot atlas id does not exist"}</li>
								</ul>
							</li>
							<li>If a parameter is missing
								<ul>
									<li>{"status":-1,"message":"Missing parameter robot_atlas_id in
										method robot.get_atlas_data"}</li>
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
				<td>robot_atlas_id</td>
				<td><input type="text" name='atlas_id'>
				</td>
			</tr>
			<tr>
				<td><input type="button" name='submit' dummy='robotDeleteAtlas'
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
	
		<form action="<?php echo($baseURL)?>robot.get_atlas_grid_metadata" method='POST'
		id='atlasGridMetadata' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Get atlas grid metadata" colspan="2"><label>Get Atlas Grid Metadata.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to Get atlas grid metadata. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.get_atlas_grid_metadata<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>id_atlas</b> :Atlas ID</li>
						</ul>
						
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										 {"status":0,"result":[{"id_grid":"3","blob_data_file_name":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_atlas_data\/13\/blob\/1356704247.jpg","version":"1"},{"id_grid":"555","blob_data_file_name":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_atlas_data\/14\/blob\/1356705494.jpg","version":"1"}]}
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
							
							<li>If id_atlas is incorrect or missing:
								<ul>
									<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
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
				<td>id_atlas</td>
				<td><input type="text" name='id_atlas'>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='atlasGridMetadata'
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