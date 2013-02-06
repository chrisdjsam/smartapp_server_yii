<?php include_once 'common_header.php';?>
	<form action="<?php echo($baseURL)?>robot.post_grid_image" method='POST'
		id='atlasPostGridImage' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "Post grid image" colspan="2"><label>Post Grid Image.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to post atlas grid image. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.post_grid_image<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>id_atlas</b> :Atlas ID</li>
							<li><b>id_grid</b> :Grid ID</li>
							<li><b>encoded_blob_data</b> :Blob data / Iamge :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
							</li>
						</ul>
					

						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										{"status":0,"result":"{\"success\":true,\"id_grid_image\":\"22\",\"id_atlas\":\"28\",\"id_grid\":\"1\",\"version\":1,\"blob_data_file_name\":\"1357583122.jpg\"}"}
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
							
							<li>If id_atlas is incorrect:
								<ul>
									<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
								</ul>
								
							<li>If grid image exist for provided 'id_atlas' and 'id_grid' combination:
								<ul>
									<li> {"status":-1,"message":"Combination of atlas id and grid id exist. Try updating for same."}</li>
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
				<td>id_grid</td>
				<td><input type="text" name='id_grid' maxlength="20">
				</td>
			</tr>
						
			<tr>
				<td>encoded_blob_data</td>
				<td><textarea rows="5" cols="20" name='encoded_blob_data'></textarea>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='atlasPostGridImage'
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

	
	<form action="<?php echo($baseURL)?>robot.update_grid_image" method='POST'
		id='atlasUpdateGridImage' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "update grid image" colspan="2"><label>Update Grid Image.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to update or add atlas grid image. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.update_grid_image<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>id_atlas</b> :Atlas ID</li>
							<li><b>id_grid</b> :Grid ID. If id does not exist, it will add new grid image</li>
							<li><b>blob_data_version</b> :BLOB data version. Pass 0 if adding new grid image.</li>
							<li><b>encoded_blob_data</b> :Blob data / Iamge :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
							</li>
						</ul>
						
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										 {"status":0,"result":"{\"success\":true,\"id_grid_image\":\"23\",\"id_atlas\":\"28\",\"id_grid\":\"0\",\"version\":1,\"blob_data_file_name\":\"1357583291.jpg\"}"}
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
							
							<li>If id_atlas is incorrect:
								<ul>
									<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
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
				<td>id_atlas</td>
				<td><input type="text" name='id_atlas'>
				</td>
			</tr>
			
			<tr>
				<td>id_grid</td>
				<td><input type="text" name='id_grid' maxlength="20">
				</td>
			</tr>
			
			<tr>
				<td>blob_data_version</td>
				<td><input type="text" name='blob_data_version'>
				</td>
			</tr>
						
			<tr>
				<td>encoded_blob_data</td>
				<td><textarea rows="5" cols="20" name='encoded_blob_data'></textarea>
				</td>
			</tr>
			
			<tr>
				<td><input type="button" name='submit' dummy='atlasUpdateGridImage'
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
	
	<form action="<?php echo($baseURL)?>robot.delete_grid_image" method='POST'
		id='atlasDeleteGridImage' class='ajaxified_forms'
		enctype="multipart/form-data">

		<table class='custom_table'>
			<tr>
				<td id = "delete grid image" colspan="2"><label>Delete Grid Image.</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" class='api_description'>
					<div class='toggle_details'>More</div>

					<div class='details_div'>
						POST method to delete atlas grid image. <br /> <br /> URL:
						<?php echo($baseURL)?>
						robot.delete_grid_image<br /> 
						Parameters:
						<ul>
							<li><b>api_key</b> :Your API Key</li>
							<li><b>id_atlas</b> :Atlas ID</li>
							<li><b>id_grid</b> :Grid ID</li>
							</li>
						</ul>
						
						Success Response:
						<ul>
							<li>If everything goes fine
								<ul>
									<li>
										 {"status":0,"result":{"success":true,"message":"You have successfully deleted grid image."}}
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
							
							<li>If id_atlas is incorrect:
								<ul>
									<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
								</ul>
								
							<li>If grid image unavailable for provided 'id_atlas' and 'id_grid' combination:
								<ul>
									<li> {"status":-1,"message":"Combination of atlas id and grid id does not exist"}</li>
								</ul>	
							</li>
							<li>If 'id_grid' is incorrect:
								<ul>
									<li> {"status":-1,"message":"Combination of atlas id and grid id does not exist"}</li>
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
				<td>id_grid</td>
				<td><input type="text" name='id_grid' maxlength="20">
				</td>
			</tr>
						
			<tr>
				<td><input type="button" name='submit' dummy='atlasDeleteGridImage'
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