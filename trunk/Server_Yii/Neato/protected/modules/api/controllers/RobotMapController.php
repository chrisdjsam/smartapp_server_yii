<?php

/**
 * The API RobotMapController is meant for all robot-map related API actions.
 */
class RobotMapController extends APIController {

	/**
	 * API to add new map data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial number of robot</li>
	 *	<li><b>xml_data</b> :XML data for robot map (Optional)</li>
	 *	<li><b>blob_data</b> :Blob data for robot map (Optional)</li>
	 *	<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
	 *	</li>
	 *</ul>
	 *Scenarios:
	 *<ul>
	 *	<li>If xml_data is not provided
	 *		<ul>
	 *			<li>It would create xml file with blank data</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If both blob_data and encoded_blob_data are not provided
	 *		<ul>
	 *			<li>It would not create blob file.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If only blob_data is provided
	 *		<ul>
	 *			<li>It would create blob file with provided blob_data file.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If only encoded_blob_data is provided
	 *		<ul>
	 *			<li>It would create blob file with provided encoded_blob_data.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If both blob_data and encoded_blob_data are provided
	 *		<ul>
	 *			<li>It would create blob file with provided encoded_blob_data.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If encoded_blob_data is provided
	 *		<ul>
	 *			<li>Blob data check for file mime type,
	 *				<ul>
	 *					<li>if file mime type is image it will check for file extension (jpg/jpeg/gif/png)</li>
	 *					<li>if file mime type is other than image it will store file with default extension <b>jpg</b></li>
	 *				</ul>
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data is provided
	 *		<ul>
	 *			<li>It would create blob file with provided blob_data file extension.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"success":true,"robot_map_id":"5","xml_data_version":1,"blob_data_version":1}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial no is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a serial number is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robot.get_maps"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionPostData(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$robot_id = $robot->id;
		$xml_data = Yii::app()->request->getParam('xml_data', '');

		$encoded_blob_data = Yii::app()->request->getParam('encoded_blob_data', '');

		if(!AppCore::validate_map_xml_data($xml_data)){
			$response_message = self::yii_api_echo('Invalid xml data.');
			self::terminate(-1, $response_message);
		}

		$robot_map_model = new RobotMap();
		$robot_map_model->id_robot = $robot_id;
		if(!$robot_map_model->save()){
			//need to work
		}

		//storing xml data
		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map_model->id;
		// Add check to see if the folder already exists
		if(!is_dir($uploads_dir_for_robot)){
			mkdir($uploads_dir_for_robot);
		}
		$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-xml-data-directory-name'];
		// Add check to see if the folder already exists
		if(!is_dir($uploads_dir)){
			mkdir($uploads_dir);
		}

		$xml_data_file_name = time(). '.xml';
		$full_file_path_xml_data = $uploads_dir. DIRECTORY_SEPARATOR . $xml_data_file_name;

		$xml_file_handle = fopen($full_file_path_xml_data, 'w');
		fwrite($xml_file_handle, $xml_data);//@todo need to handle file write exceptions
		fclose($xml_file_handle);
		$xml_data_file_version = 1;

		$robot_map_xml_data_model = new RobotMapXmlDataVersion();
		$robot_map_xml_data_model->id_robot_map = $robot_map_model->id;
		$robot_map_xml_data_model->version = $xml_data_file_version;
		if(!$robot_map_xml_data_model->save()){
			//need to work
		}

		//storing blob data
		$blob_data_file_name = '';
		$blob_data_file_version = 1;
		$uploads_dir = '';

		if($encoded_blob_data !== ''){
			$decoded_blob_data = base64_decode($encoded_blob_data);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
			finfo_close($f);
			$blob_data_file_extension = 'jpg';
			if(strpos($mime_type, "image")!== false){
				$blob_data_file_extension = str_replace("image/","",$mime_type);
			}

			$blob_data_file_name = time(). "." .$blob_data_file_extension;

			$blob_data = $decoded_blob_data;

			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map_model->id;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot)){
				mkdir($uploads_dir_for_robot);
			}
			$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-blob-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}
			$full_file_path_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $blob_data_file_name;

			$blob_file_handle = fopen($full_file_path_blob_data, 'w');
			fwrite($blob_file_handle, $blob_data); //@todo need to handle file write exceptions
			fclose($blob_file_handle);
		}
		elseif(isset($_FILES['blob_data'])){
			$blob_data_temp_file_path = $_FILES['blob_data']['tmp_name'];
			$blob_data_file_extension = pathinfo($_FILES['blob_data']['name'], PATHINFO_EXTENSION);
			$blob_data_file_name = time(). "." .$blob_data_file_extension;

			$handle = fopen($blob_data_temp_file_path, "r");
			$blob_data = fread($handle, filesize($blob_data_temp_file_path));
			fclose($handle);

			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map_model->id;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot)){
				mkdir($uploads_dir_for_robot);
			}
			$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-blob-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}
			$full_file_path_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $blob_data_file_name;

			$blob_file_handle = fopen($full_file_path_blob_data, 'w');
			fwrite($blob_file_handle, $blob_data); //@todo need to handle file write exceptions
			fclose($blob_file_handle);
		}

		$robot_map_blob_data_model = new RobotMapBlobDataVersion();
		$robot_map_blob_data_model->id_robot_map = $robot_map_model->id;
		$robot_map_blob_data_model->version = $blob_data_file_version;
		if(!$robot_map_blob_data_model->save()){
			//need to work
		}

		$robot_map_model->xml_data_file_name = $xml_data_file_name;
		$robot_map_model->blob_data_file_name = $blob_data_file_name;

		if($robot_map_model->save()){
			$response_message = self::yii_api_echo('Robot Map data stored successfully.');
			$response_data = array("success"=>true, "robot_map_id"=>$robot_map_model->id, "xml_data_version"=>$xml_data_file_version, "blob_data_version"=>$blob_data_file_version);
			self::success($response_data);
		}
	}

	/**
	 * API to update map data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>map_id</b> :Robot Map Id</li>
	 *	<li><b>xml_data_version</b> :XML data version</li>
	 *	<li><b>xml_data</b> :XML data for robot map</li>
	 *	<li><b>blob_data_version</b> :Blob data version</li>
	 *	<li><b>blob_data</b> :Blob data for robot map</li>
	 *	<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
	 *	</li>
	 *</ul>
	 *Scenarios:
	 *<ul>
	 *	<li>If xml data version provided and xml data field is blank
	 *		<ul>
	 *			<li>It would update previous xml data with blank data</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and both blob_data and encoded_blob_data are not provided
	 *		<ul>
	 *			<li>It would delete previous blob data file</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and only blob_data is provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob file with provided blob_data file.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and only encoded_blob_data is provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob file with provided encoded_blob_data.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and both blob_data and encoded_blob_data are provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob file with provided encoded_blob_data.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and encoded_blob_data is provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and blob data check for file mime type,
	 *				<ul>
	 *					<li>if file mime type is image it will check for file extension (jpg/jpeg/gif/png)</li>
	 *					<li>if file mime type is other than image it will store file with default extension <b>jpg</b></li>
	 *				</ul>
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and blob_data is provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob file with provided blob_data file extension.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If xml data version provided and goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot map data."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If blob data version provided and goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot map data."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If both xml and blob data version provided,everything goes
	 *		fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot map data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses: <br />
	 *<ul>
	 *	<li>If robot map id is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot map id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If both the data versions are missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Provide at least one data
	 *				version(xml or blob)."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If xml data version is provided, not matching with latest xml
	 *		data version
	 *		<ul>
	 *			<li>{"status":-1,"message":"Version mismatch for xml data."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If blob data version is provided, not matching with latest
	 *		blob data version
	 *		<ul>
	 *			<li>{"status":-1,"message":"Version mismatch for blob data."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_map_id in
	 *				method robot.updat_map_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionUpdateData(){
		$robot_map_id = Yii::app()->request->getParam('map_id', '');
		$robot_map_model = self::verify_for_robot_map_id_existence($robot_map_id);

		$robot_xml_data_version = Yii::app()->request->getParam('xml_data_version', '');
		$robot_blob_data_version = Yii::app()->request->getParam('blob_data_version', '');

		$robot_xml_data_latest_version = $robot_map_model->XMLDataLatestVersion;
		$robot_blob_data_latest_version = $robot_map_model->BlobDataLatestVersion;

		$encoded_blob_data = Yii::app()->request->getParam('encoded_blob_data', '');

		if ($robot_xml_data_version || $robot_blob_data_version){

			if($robot_xml_data_version && $robot_xml_data_version != $robot_xml_data_latest_version){
				$response_message = self::yii_api_echo('Version mismatch for xml data.');
				self::terminate(-1, $response_message);
			}

			if($robot_blob_data_version && $robot_blob_data_version != $robot_blob_data_latest_version){
				$response_message = self::yii_api_echo('Version mismatch for blob data.');
				self::terminate(-1, $response_message);
			}

			$old_xml_data_file_path = '';
			$old_blob_data_file_path = '';

			if ($robot_xml_data_version){
				$xml_data = Yii::app()->request->getParam('xml_data', '');

				if(!AppCore::validate_map_xml_data($xml_data)){
					$response_message = self::yii_api_echo('Invalid xml data.');
					self::terminate(-1, $response_message);
				}
				//storing xml data

				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map_id;

				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-xml-data-directory-name'];

				$xml_data_file_name = time(). '.xml';
				$full_file_path_xml_data = $uploads_dir. DIRECTORY_SEPARATOR . $xml_data_file_name;
				$old_xml_data_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $robot_map_model->xml_data_file_name;

				$xml_file_handle = fopen($full_file_path_xml_data, 'w');
				fwrite($xml_file_handle,$xml_data);//@todo need to handle file write exceptions
				fclose($xml_file_handle);

				$xml_data_file_version = $robot_xml_data_latest_version + 1;
				$robot_map_model->xml_data_file_name = $xml_data_file_name;


				$robot_map_xml_data_model = new RobotMapXmlDataVersion();
				$robot_map_xml_data_model->id_robot_map = $robot_map_id;
				$robot_map_xml_data_model->version = $xml_data_file_version;
				if(!$robot_map_xml_data_model->save()){
					//need to work
				}
			}

			//storing blob data
			if ($robot_blob_data_version){
				$blob_data_file_name = '';

				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map_id;

				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-blob-data-directory-name'];

				if($encoded_blob_data !== ''){
					$decoded_blob_data = base64_decode($encoded_blob_data);
					$f = finfo_open();
					$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
					finfo_close($f);

					$blob_data_file_extension = 'jpg';
					if(strpos($mime_type, "image")!== false){
						$blob_data_file_extension = str_replace("image/","",$mime_type);
					}

					$blob_data_file_name = time(). "." .$blob_data_file_extension;

					$blob_data = $decoded_blob_data;

					// Add check to see if the folder already exists
					if(!is_dir($uploads_dir)){
						mkdir($uploads_dir);
					}
					$full_file_path_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $blob_data_file_name;

					$blob_file_handle = fopen($full_file_path_blob_data, 'w');
					fwrite($blob_file_handle, $blob_data); //@todo need to handle file write exceptions
					fclose($blob_file_handle);
				}
				elseif(isset($_FILES['blob_data'])){
					$blob_data_temp_file_path = $_FILES['blob_data']['tmp_name'];
					$blob_data_file_extension = pathinfo($_FILES['blob_data']['name'], PATHINFO_EXTENSION);
					$blob_data_file_name = time(). "." .$blob_data_file_extension;

					$temp_file_handle = fopen($blob_data_temp_file_path, "r");
					$blob_data = fread($temp_file_handle, filesize($blob_data_temp_file_path));
					fclose($temp_file_handle);

					// Add check to see if the folder already exists
					if(!is_dir($uploads_dir)){
						mkdir($uploads_dir);
					}
					$full_file_path_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $blob_data_file_name;

					$blob_file_handle = fopen($full_file_path_blob_data, 'w');
					fwrite($blob_file_handle, $blob_data);//@todo need to handle file write exceptions
					fclose($blob_file_handle);
				}

				if($robot_map_model->blob_data_file_name != ''){
					$old_blob_data_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $robot_map_model->blob_data_file_name;
				}

				$blob_data_file_version = $robot_blob_data_latest_version + 1;
				$robot_map_model->blob_data_file_name = $blob_data_file_name;

				$robot_map_blob_data_model = new RobotMapBlobDataVersion();
				$robot_map_blob_data_model->id_robot_map = $robot_map_id;
				$robot_map_blob_data_model->version = $blob_data_file_version;
				if(!$robot_map_blob_data_model->save()){
					//need to work
				}
			}

			$robot_map_model->updated_on = date("Y-m-d H:i:s");

			if($robot_map_model->update()){
				if($old_xml_data_file_path != ''){
					unlink($old_xml_data_file_path);
				}
				if($old_blob_data_file_path != ''){
					unlink($old_blob_data_file_path);
				}
				$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully updated robot map data.'));
				self::success($response_data);
			}

		}else{
			$response_message = self::yii_api_echo('Provide at least one data version(xml or blob).');
			self::terminate(-1, $response_message);
		}
	}

	/**
	 *API to get robot maps
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":[{"id":"1","xml_data_version":"2","blob_data_version":"1"},{"id":"2","xml_data_version":"3","blob_data_version":"1"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and map does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":[]}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":""Serial number does not exist""}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetMaps(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot_model = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$robot_map_arr = array();
		foreach ($robot_model->robotMaps as $robot_map){
			$robot_map_details = array();
			$robot_map_details['id'] = $robot_map->id;
			$robot_map_details['xml_data_version'] = $robot_map->XMLDataLatestVersion;
			$robot_map_details['blob_data_version'] = $robot_map->BlobDataLatestVersion;

			$robot_map_arr[] = $robot_map_details;
		}
		self::success($robot_map_arr);
	}

	/**
	 * API to get robot map data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_map_id</b> :Robot Map Id</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353061075.xml","blob_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/blob\/Koala.jpg"}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and blob data file is not exist
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353397443.xml","blob_data_url":""}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot map id is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot map id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_map_id in
	 *				method robot.get_map_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetData(){
		$robot_map_id = Yii::app()->request->getParam('robot_map_id', '');
		$robot_map = self::verify_for_robot_map_id_existence($robot_map_id);

		$response_data = array("xml_data_url" => $robot_map->XMLDataURL,
				"blob_data_url" => $robot_map->BlobDataURL);
		self::success($response_data);
	}

	/**
	 * API to delete robot map data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_map_id</b> :Robot Map Id</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully deleted robot map data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot map id is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot map id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_map_id in
	 *				method robot.delete_map"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionDeleteMapData(){
		$robot_map_id = Yii::app()->request->getParam('robot_map_id', '');
		$robot_map = self::verify_for_robot_map_id_existence($robot_map_id);

		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map->id;
		if($robot_map->delete()){
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot);

			$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully deleted robot map data.'));
			self::success($response_data);
		}

	}

	/**
	 * Deletes a particular robot map.
	 * It is called by ajax call.
	 */
	public function actionDeleteMap()
	{
		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		if(RobotMap::model()->deleteByPk($id)){
			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $id;
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot);

			$content = array('status' => 0);
		}else{
			$content = array('status' => -1);
		}

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}
}