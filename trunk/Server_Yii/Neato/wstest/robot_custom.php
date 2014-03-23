
<?php include_once 'common_header.php';?>
<form action="<?php echo($baseURL)?>robot.post_custom_data" method='POST' id='robotPostcustomdata' class='ajaxified_forms'
	enctype="multipart/form-data">
	<table class='custom_table'>
		<tr>
			<td id="Post Robot Custom Data" colspan="2">
				<label>Post Robot Custom Data</label>
			</td>
		</tr>
		<tr>
			<td colspan="2" class='api_description'>
				<div class='toggle_details'>More</div>
				<div class='details_div'>
					POST method to post robot custom data.
					<br />
					<br />
					URL:
					<?php echo($baseURL)?>
					robot.post_custom_data
					<br />
					Parameters:
					<ul>
						<li>
							<b>api_key</b>
							:Your API Key
						</li>
						<li>
							<b>serial_number</b>
							:Serial Number of robot
						</li>
						<li>
							<b>encoded_blob_data[]</b>
							:Array of Custom Data of key=>value pairs, e.g. encoded_blob_data{'history'=>'encoded data', 'recent'=>'encoded data'}.The
							key is the type and value is in base 64 encoded string.You can generate base 64 encoded string for a file using this
							<a href='robot_data_encode.php' target='_blank'>link</a>
						</li>
						<li>
							<b>blob_data[]</b>
							:Array of Custom Data of key=>value pairs, e.g. blob_data{'history'=>'robot.jpg', 'recent'=>'room.xml'}
						</li>
					</ul>
					Scenarios
					<ul>
						<li>
							If keys and only blob_data[] is provided
							<ul>
								<li>It would create blob file with provided blob_data[] file extension.</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If keys and only encoded_blob_data[] is provided
							<ul>
								<li>It would create blob file with provided encoded_blob_data[].</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If keys, blob_data[] and encoded_blob_data[] are provided
							<ul>
								<li>It would create blob file with provided encoded_blob_data[].</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If encoded_blob_data[] is provided
							<ul>
								<li>
									Blob data check for file mime type,
									<ul>
										<li>if file mime type is image it will check for file extension (jpg/jpeg/gif/png)</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							The encoded_blob_data[] and blob_data[] files to be supported
							<ul>
								<li>Only jpg/jpeg/gif/png files are supported by custom data</li>
							</ul>
						</li>
					</ul>
					Success Response:
					<ul>
						<li>
							If everything goes fine
							<ul>
								<li>{"status":0,"result":{"success":true,"robot_custom_id":"2","history":1,"recent":1}}</li>
							</ul>
						</li>
					</ul>
					Failure Responses:
					<br />
					<ul>
						<li>
							If API Key is missing:
							<ul>
								<li>{"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication
									failed"}}</li>
							</ul>
						</li>
						<li>
							If serial number does not exist
							<ul>
								<li>{"status":-1,"message":"Robot serial number does not exist","error":{"code":"-114","message":"Serial number does not
									exist."}}</li>
							</ul>
						</li>
						<li>
							If serial number provided but keys not provided.
							<ul>
								<li>{"status":-1,"message":"Provide atleast one data.","error":{"code":"-102","message":"Missing parameter in method
									call"}}</li>
							</ul>
						</li>
						<li>
							If serial number and key provided but both blob_data[] and encoded_blob_data[] are not provided.
							<ul>
								<li>{"status":-1,"message":"Provide atleast one data.","error":{"code":"-102","message":"Missing parameter in method
									call"}}</li>
							</ul>
						</li>
						<li>
							If a parameter(serial number) is missing
							<ul>
								<li>{"status":-1,"message":"Missing parameter serial_number in method
									robot.post_custom_data","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
						<li>
							If files not supported
							<ul>
								<li>{"status":-1,"message":"Only jpg\/jpeg\/gif\/png files are supported by custom
									data","error":{"code":"-157","message":"Unsupported file type"}}</li>
							</ul>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td class='label_field'>api_key</td>
			<td class='value_field'>
				<input type="text" name='api_key' class='api_keys' value='<?php echo($api_key);?>' />
			</td>
		</tr>
		<tr>
			<td>serial_number</td>
			<td>
				<input type="text" name='serial_number'>
			</td>
		</tr>
		<tr>
			<td id='labelPlaceholderRow' colspan="2"></td>
		</tr>
		<tr>
			<td>
				<input type="text" name='labelName' value='' id='labelName2' class='removeFromRequest'>
			</td>
			<td>
				<div id='addLabelLink2'>Add File Detail Key (considered keys are name)</div>
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" name='submit' dummy='robotPostcustomdata' value='Submit' class='submit_form'>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class='request_div'>View Request</div>
				<br />
				<div class='response_div'>View Response</div>
			</td>
		</tr>
	</table>
</form>
<form action="<?php echo($baseURL)?>robot.get_customs" method='POST' id='robotGetcustoms' class='ajaxified_forms'
	enctype="multipart/form-data">
	<table class='custom_table'>
		<tr>
			<td id="Get Robot Customs" colspan="2">
				<label>Get Robot Customs</label>
			</td>
		</tr>
		<tr>
			<td colspan="2" class='api_description'>
				<div class='toggle_details'>More</div>
				<div class='details_div'>
					POST method to get robot customs.
					<br />
					<br />
					URL:
					<?php echo($baseURL)?>
					robot.get_customs
					<br />
					Parameters:
					<ul>
						<li>
							<b>api_key</b>
							:Your API Key
						</li>
						<li>
							<b>serial_number</b>
							:Serial Number of robot
						</li>
					</ul>
					Success Responses:
					<ul>
						<li>
							If everything goes fine
							<ul>
								<li>{"status":0,"result":[{"id":"9","history":"1","recent":"1","image":"1"},{"id":"10","img":"1"}]}</li>
							</ul>
						</li>
						<li>
							If everything goes fine and custom does not exist
							<ul>
								<li>{"status":0,"result":[]}</li>
							</ul>
						</li>
					</ul>
					Failure Responses:
					<br />
					<ul>
						<li>
							If API Key is missing:
							<ul>
								<li>{"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication
									failed"}}</li>
							</ul>
						</li>
						<li>
							If serial number does not exist
							<ul>
								<li>{"status":-1,"message":"Robot serial number does not exist","error":{"code":"-114","message":"Serial number does not
									exist."}}</li>
							</ul>
						</li>
						<li>
							If a parameter(serial number) is missing
							<ul>
								<li>{"status":-1,"message":"Missing parameter serial_number in method
									robot.get_customs","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td class='label_field'>api_key</td>
			<td class='value_field'>
				<input type="text" name='api_key' class='api_keys' value='<?php echo($api_key);?>' />
			</td>
		</tr>
		<tr>
			<td>serial_number</td>
			<td>
				<input type="text" name='serial_number'>
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" name='submit' dummy='robotGetcustoms' value='Submit' class='submit_form'>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class='request_div'>View Request</div>
				<br />
				<div class='response_div'>View Response</div>
			</td>
		</tr>
	</table>
</form>
<form action="<?php echo($baseURL)?>robot.get_custom_data" method='POST' id='robotGetcustomdata' class='ajaxified_forms'
	enctype="multipart/form-data">
	<table class='custom_table'>
		<tr>
			<td id="Get Robot Custom Data" colspan="2">
				<label>Get Robot Custom Data</label>
			</td>
		</tr>
		<tr>
			<td colspan="2" class='api_description'>
				<div class='toggle_details'>More</div>
				<div class='details_div'>
					POST method to get robot custom data.
					<br />
					<br />
					URL:
					<?php echo($baseURL)?>
					robot.get_custom_data
					<br />
					Parameters:
					<ul>
						<li>
							<b>api_key</b>
							:Your API Key
						</li>
						<li>
							<b>robot_custom_id</b>
							:Robot Custom Id
						</li>
					</ul>
					Success Response:
					<ul>
						<li>
							If everything goes fine
							<ul>
								<li>
									{"status":0,"result":[{"recent":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_custom_data\/8\/1354636168_recent.jpg"},{"image":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_custom_data\/8\/1354636168_image.jpg"}]}
								</li>
							</ul>
						</li>
					</ul>
					Failure Responses:
					<br />
					<ul>
						<li>
							If API Key is missing:
							<ul>
								<li>{"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication
									failed"}}</li>
							</ul>
						</li>
						<li>
							If robot custom id does not exist
							<ul>
								<li>{"status":-1,"message":"Robot custom id does not exist","error":{"code":"-138","message":"Robot custom id does not
									exist."}}</li>
							</ul>
						</li>
						<li>
							If a parameter(robot_custom_id) is missing
							<ul>
								<li>{"status":-1,"message":"Missing parameter robot_custom_id in method
									robot.get_custom_data","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td class='label_field'>api_key</td>
			<td class='value_field'>
				<input type="text" name='api_key' class='api_keys' value='<?php echo($api_key);?>' />
			</td>
		</tr>
		<tr>
			<td>robot_custom_id</td>
			<td>
				<input type="text" name='robot_custom_id'>
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" name='submit' dummy='robotGetcustomdata' value='Submit' class='submit_form'>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class='request_div'>View Request</div>
				<br />
				<div class='response_div'>View Response</div>
			</td>
		</tr>
	</table>
</form>
<form action="<?php echo($baseURL)?>robot.update_custom_data" method='POST' id='robotUpdatecustomdata' class='ajaxified_forms'
	enctype="multipart/form-data">
	<table class='custom_table'>
		<tr>
			<td id="Update Robot Custom Data" colspan="2">
				<label>Update Robot Custom Data</label>
			</td>
		</tr>
		<tr>
			<td colspan="2" class='api_description'>
				<div class='toggle_details'>More</div>
				<div class='details_div'>
					POST method to update robot custom data.
					<br />
					<br />
					URL:
					<?php echo($baseURL)?>
					robot.update_custom_data
					<br />
					Parameters:
					<ul>
						<li>
							<b>api_key</b>
							:Your API Key
						</li>
						<li>
							<b>robot_custom_id</b>
							:Robot Custom Id
						</li>
						<li>
							<b>blob_data_version[]</b>
							:Array of Data Version of key=>value pairs,e.g data_version{'history'=>'1', 'recent'=>'1'}
						</li>
						<li>
							<b>encoded_blob_data[]</b>
							:(Optional)Array of Custom Data of key=>value pairs, e.g. encoded_blob_data{'history'=>'encoded data', 'recent'=>'encoded
							data'}.The key is the type and value is in base 64 encoded string.You can generate base 64 encoded string for a file using
							this
							<a href='robot_data_encode.php' target='_blank'>link</a>
						</li>
						<li>
							<b>blob_data[]</b>
							:(Optional)Array of Custom Data of key=>value pairs, e.g. blob_data{'history'=>'robot.jpg', 'recent'=>'room.xml'}
						</li>
					</ul>
					Scenarios
					<ul>
						<li>
							If blob_data_version[] provided and both encoded_blob_data[] and blob_data[] are not provided
							<ul>
								<li>It would delete previous blob data file</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If blob_data_version[] provided and only blob_data[] is provided
							<ul>
								<li>It would delete previous blob data file and create blob file with provided blob_data[] file.</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If blob_data_version[] provided and both encoded_blob_data[] and blob_data[] are provided
							<ul>
								<li>It would delete previous blob data file and create blob file with provided encoded_blob_data[].</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If blob_data_version[] provided and only encoded_blob_data[] are provided
							<ul>
								<li>It would delete previous blob data file and create blob file with provided encoded_blob_data[].</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If encoded_blob_data[] is provided
							<ul>
								<li>
									Blob data check for file mime type,
									<ul>
										<li>if file mime type is image it will check for file extension (jpg/jpeg/gif/png)</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							The encoded_blob_data[] and blob_data[] files to be supported
							<ul>
								<li>Only jpg/jpeg/gif/png files are supported by custom data</li>
							</ul>
						</li>
					</ul>
					Success Responses:
					<ul>
						<li>
							If blob_data_version[] provided and goes fine
							<ul>
								<li>{"status":0,"result":{"success":true,"message":"You have successfully updated robot custom data."}}</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If blob_data_version[] and encoded_blob_data[] are provided everything goes fine
							<ul>
								<li>{"status":0,"result":{"success":true,"message":"You have successfully updated robot custom data."}}</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If blob_data_version[] and blob_data[] are provided everything goes fine
							<ul>
								<li>{"status":0,"result":{"success":true,"message":"You have successfully updated robot custom data."}}</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							If blob_data_version[], encoded_blob_data[] and blob_data[] are provided everything goes fine
							<ul>
								<li>{"status":0,"result":{"success":true,"message":"You have successfully updated robot custom data."}}</li>
							</ul>
						</li>
					</ul>
					Failure Responses:
					<br />
					<ul>
						<li>
							If API Key is missing:
							<ul>
								<li>{"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication
									failed"}}</li>
							</ul>
						</li>
						<li>
							If robot custom id does not exist
							<ul>
								<li>{"status":-1,"message":"Robot custom id does not exist","error":{"code":"-138","message":"Robot custom id does not
									exist."}}</li>
							</ul>
						</li>
						<li>
							If custom id provided but keys not provided.
							<ul>
								<li>{"status":-1,"message":"Missing parameter robot_custom_id in method
									robot.get_custom_data","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
						<li>
							If custom id and keys provided but blob_data_version[],blob_data[] and encoded_blob_data[] are not provided.
							<ul>
								<li>{"status":-1,"message":"Missing parameter robot_custom_id in method
									robot.get_custom_data","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
						<li>
							If custom id ,keys and blob_data_version[] are provided but blob_data_version[] not matching with latest blob_data_version.
							<ul>
								<li>{"status":-1,"message":"Version mismatch for (key_name)", "error":{"code":-130, "message":"Version mismatch for blob
									data."}}</li>
							</ul>
						</li>
						<li>
							If custom id and keys and blob_data_version[] provided but keys are not exist
							<ul>
								<li>{"status":-1,"message":"(key_name) not found.", "error":{"code":-129, "message":"Version mismatch for xml data"}}</li>
							</ul>
						</li>
						<li>
							If a parameter(robot_custom_id) is missing
							<ul>
								<li>{"status":-1,"message":"Missing parameter robot_custom_id in method
									robot.update_custom_data","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
						<li>
							If files not supported
							<ul>
								<li>{"status":-1,"message":"Only jpg\/jpeg\/gif\/png files are supported by custom
									data","error":{"code":"-157","message":"Unsupported file type"}}</li>
							</ul>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td class='label_field'>api_key</td>
			<td class='value_field'>
				<input type="text" name='api_key' class='api_keys' value='<?php echo($api_key);?>' />
			</td>
		</tr>
		<tr>
			<td>robot_custom_id</td>
			<td>
				<input type="text" name='robot_custom_id'>
			</td>
		</tr>
		<!-- <tr>
				<td>data_version</td>
				<td><input type="text" name='data_version'>
				</td>
			</tr> -->
		<tr>
			<td id='labelPlaceholderRowUpdate' colspan="2"></td>
		</tr>
		<tr>
			<td>
				<input type="text" name='labelName' value='' id='labelNameUpdate' class='removeFromRequest'>
			</td>
			<td>
				<div id='addLabelLinkUpdate'>Add File Detail Key (considered keys are name)</div>
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" name='submit' dummy='robotUpdatecustomdata' value='Submit' class='submit_form'>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class='request_div'>View Request</div>
				<br />
				<div class='response_div'>View Response</div>
			</td>
		</tr>
	</table>
</form>
<form action="<?php echo($baseURL)?>robot.delete_custom_data" method='POST' id='robotDeletecustomdata' class='ajaxified_forms'>
	<table class='custom_table'>
		<tr>
			<td id="Delete Robot Custom Data" colspan="2">
				<label>Delete Robot Custom Data</label>
			</td>
		</tr>
		<tr>
			<td colspan="2" class='api_description'>
				<div class='toggle_details'>More</div>
				<div class='details_div'>
					POST method to delete robot custom data.
					<br />
					<br />
					URL:
					<?php echo($baseURL)?>
					robot.delete_custom_data
					<br />
					Parameters:
					<ul>
						<li>
							<b>api_key</b>
							:Your API Key
						</li>
						<li>
							<b>robot_custom_id</b>
							:Robot Custom Id
						</li>
					</ul>
					Success Response:
					<ul>
						<li>
							If everything goes fine
							<ul>
								<li>{"status":0,"result":{"success":true,"message":"You have successfully deleted robot custom data."}}</li>
							</ul>
						</li>
					</ul>
					Failure Responses:
					<br />
					<ul>
						<li>
							If API Key is missing:
							<ul>
								<li>{"status":-1,"message":"User could not be authenticated", "error":{"code":"-174","message":"User authentication
									failed"}}</li>
							</ul>
						</li>
						<li>
							If robot custom id does not exist
							<ul>
								<li>{"status":-1,"message":"Robot custom id does not exist","error":{"code":"-138","message":"Robot custom id does not
									exist."}}</li>
							</ul>
						</li>
						<li>
							If a parameter(robot_custom_id) is missing
							<ul>
								<li>{"status":-1,"message":"Missing parameter robot_custom_id in method
									robot.delete_custom_data","error":{"code":"-102","message":"Missing parameter in method call"}}</li>
							</ul>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td class='label_field'>api_key</td>
			<td class='value_field'>
				<input type="text" name='api_key' class='api_keys' value='<?php echo($api_key);?>' />
			</td>
		</tr>
		<tr>
			<td>robot_custom_id</td>
			<td>
				<input type="text" name='robot_custom_id'>
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" name='submit' dummy='robotDeletecustomdata' value='Submit' class='submit_form'>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class='request_div'>View Request</div>
				<br />
				<div class='response_div'>View Response</div>
			</td>
		</tr>
	</table>
</form>
<?php include_once 'common_footer.php';?>
