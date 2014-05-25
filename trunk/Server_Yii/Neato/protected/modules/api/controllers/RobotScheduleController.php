<?php

/**
 * The API RobotScheduleController is meant for all robot-schedule related API actions.
 */
class RobotScheduleController extends APIController {

	/**
	 * API to add new schedule data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *	<li><b>schedule_type</b> :Basic OR Advanced</li>
	 *	<li><b>xml_data</b> :XML data for robot schedule (Optional)</li>
	 *	<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
	 *	</li>
	 *	<li><b>blob_data</b> :Blob data for robot schedule (Optional)</li>
	 *</ul>
	 *Scenarios:
	 *<ul>
	 *	<li>If xml data is not provided
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
	 *				{"status":0,"result":{"success":true,"robot_schedule_id":"5","schedule_type":"Advanced","xml_data_version":1,"blob_data_version":1}}
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
	 *				method robotschedule.post_data"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If schdule type not valid or missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot schedule type is not valid"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionPostData(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$robot_id = $robot->id;
		$robot_schedule_type = Yii::app()->request->getParam('schedule_type', '');
		$xml_data = Yii::app()->request->getParam('xml_data', '');

		$encoded_blob_data = Yii::app()->request->getParam('encoded_blob_data', '');

		if(!AppCore::validate_schedule_xml_data($xml_data)){
			$response_message = self::yii_api_echo('Invalid xml data.');
			self::terminate(-1, $response_message, APIConstant::INVALID_XML);
		}

		$robot_schedule_model = new RobotSchedule();
		$robot_schedule_model->id_robot = $robot_id;

		if(self::verify_robot_schedule_type($robot_schedule_type)){
			$robot_schedule_model->type = $robot_schedule_type;
		}

		if(!$robot_schedule_model->save()){
			//TODO
		}

		//storing xml data

		$schedule_xml_data_file_name = '';
		$schedule_xml_data_file_version = 1;
		$uploads_dir = '';

		if($xml_data) {

			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_model->id;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot_schedule)){
				mkdir($uploads_dir_for_robot_schedule);
			}
			$uploads_dir = $uploads_dir_for_robot_schedule . DIRECTORY_SEPARATOR . Yii::app()->params['robot-schedule_xml-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}

			$schedule_xml_data_file_name = time(). '.xml';
			$full_file_path_schedule_xml_data = $uploads_dir. DIRECTORY_SEPARATOR . $schedule_xml_data_file_name;

			$schedule_xml_file_handle = fopen($full_file_path_schedule_xml_data, 'w');
			fwrite($schedule_xml_file_handle, $xml_data);//@todo need to handle file write exceptions
			fclose($schedule_xml_file_handle);
			$schedule_xml_data_file_version = 1;

		}

		$robot_schedule_xml_data_model = new RobotScheduleXmlDataVersion();
		$robot_schedule_xml_data_model->id_robot_schedule = $robot_schedule_model->id;
		$robot_schedule_xml_data_model->version = $schedule_xml_data_file_version;
		if(!$robot_schedule_xml_data_model->save()){
			//TODO
		}

		//storing blob data
		$schedule_blob_data_file_name = '';
		$schedule_blob_data_file_version = 1;
		$uploads_dir = '';

		if($encoded_blob_data !== ''){
			$decoded_blob_data = base64_decode($encoded_blob_data);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
			finfo_close($f);
			$schedule_blob_data_file_extension = 'jpg';
			if(strpos($mime_type, "image")!== false){
				$schedule_blob_data_file_extension = str_replace("image/","",$mime_type);
			}

			$schedule_blob_data_file_name = time(). "." .$schedule_blob_data_file_extension;
			$blob_data = $decoded_blob_data;

			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_model->id;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot_schedule)){
				mkdir($uploads_dir_for_robot_schedule);
			}
			$uploads_dir = $uploads_dir_for_robot_schedule . DIRECTORY_SEPARATOR . Yii::app()->params['robot-schedule_blob-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}
			$full_file_path_schedule_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $schedule_blob_data_file_name;

			$schedule_blob_file_handle = fopen($full_file_path_schedule_blob_data, 'w');
			fwrite($schedule_blob_file_handle, $blob_data); //@todo need to handle file write exceptions
			fclose($schedule_blob_file_handle);
		}
		elseif(isset($_FILES['blob_data'])){
			$schedule_blob_data_temp_file_path = $_FILES['blob_data']['tmp_name'];
			$schedule_blob_data_file_extension = pathinfo($_FILES['blob_data']['name'], PATHINFO_EXTENSION);
			$schedule_blob_data_file_name = time(). "." .$schedule_blob_data_file_extension;

			$handle = fopen($schedule_blob_data_temp_file_path, "r");
			$blob_data = fread($handle, filesize($schedule_blob_data_temp_file_path));
			fclose($handle);

			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_model->id;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot_schedule)){
				mkdir($uploads_dir_for_robot_schedule);
			}
			$uploads_dir = $uploads_dir_for_robot_schedule . DIRECTORY_SEPARATOR . Yii::app()->params['robot-schedule_blob-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}
			$full_file_path_schedule_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $schedule_blob_data_file_name;

			$schedule_blob_file_handle = fopen($full_file_path_schedule_blob_data, 'w');
			fwrite($schedule_blob_file_handle, $blob_data); //@todo need to handle file write exceptions
			fclose($schedule_blob_file_handle);
		}

		$robot_schedule_blob_data_model = new RobotScheduleBlobDataVersion();
		$robot_schedule_blob_data_model->id_robot_schedule = $robot_schedule_model->id;
		$robot_schedule_blob_data_model->version = $schedule_blob_data_file_version;
		if(!$robot_schedule_blob_data_model->save()){
			//TODO
		}

		$robot_schedule_model->xml_data_file_name = $schedule_xml_data_file_name;
		$robot_schedule_model->blob_data_file_name = $schedule_blob_data_file_name;

		if($robot_schedule_model->update()){

			$response_message = self::yii_api_echo('Robot schedule data stored successfully.');
			$response_data = array("success"=>true, "robot_schedule_id"=>$robot_schedule_model->id,"schedule_type" =>$robot_schedule_model->type, "xml_data_version"=>$schedule_xml_data_file_version, "blob_data_version"=>$schedule_blob_data_file_version, 'schedule_version' => $robot_schedule_model->XMLDataLatestVersion);
			self::success($response_data);
		}
	}

	/**
	 * API to update schedule data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_schedule_id</b> :Robot Schedule Id</li>
	 *	<li><b>schedule_type</b> :Basic OR Advanced (Optional)</li>
	 *	<li><b>xml_data_version</b> :XML data version</li>
	 *	<li><b>xml_data</b> :XML data for robot schedule (Optional)</li>
	 *	<li><b>blob_data_version</b> :Blob data version</li>
	 *	<li><b>encoded_blob_data</b> :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
	 *	</li>
	 *	<li><b>blob_data</b> :Blob data for robot schedule (Optional)</li>
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
	 *			<li>It would not create blob file.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and only blob_data is provided
	 *		<ul>
	 *			<li>It would create blob file with provided blob_data file.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and only encoded_blob_data is provided
	 *		<ul>
	 *			<li>It would create blob file with provided encoded_blob_data.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and both blob_data and encoded_blob_data are provided
	 *		<ul>
	 *			<li>It would create blob file with provided encoded_blob_data.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob data version provided and encoded_blob_data is provided
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
	 *	<li>If blob data version provided and blob_data is provided
	 *		<ul>
	 *			<li>It would create blob file with provided blob_data file extension.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If xml data version and blob data version are provided but
	 *		schedule type is same as previous schedule type
	 *		<ul>
	 *			<li>It would not change anything and you will get message as
	 *				"Robot schedule data is upto date"</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If xml data version provided and goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot schedule data."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If blob data version provided and goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot schedule data."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If both xml and blob data version provided,everything goes
	 *		fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot schedule data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot schedule id is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot schedule id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If parameter robot schedule id is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_schedule_id
	 *				in method robotschedule.update_data"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If schdule type not valid or missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot schedule type is not valid"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If both the data versions are missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Provide at least one data
	 *				version(xml or blob) or schedule type."}</li>
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
	 *</ul>
	 */
	public function actionUpdateData(){
		$robot_schedule_id = Yii::app()->request->getParam('robot_schedule_id', '');
		$robot_schedule_model = self::verify_for_robot_schedule_id_existence($robot_schedule_id);

		$robot_schedule_type = Yii::app()->request->getParam('schedule_type', '');
		$old_robot_schedule_type = $robot_schedule_model->type;

		$robot_schedule_xml_data_version = Yii::app()->request->getParam('xml_data_version', '');
		$robot_schedule_blob_data_version = Yii::app()->request->getParam('blob_data_version', '');

		$encoded_blob_data = Yii::app()->request->getParam('encoded_blob_data', '');

		$robot_schedule_xml_data_latest_version = $robot_schedule_model->XMLDataLatestVersion;
		$robot_schedule_blob_data_latest_version = $robot_schedule_model->BlobDataLatestVersion;

		if($robot_schedule_type){
			if(self::verify_robot_schedule_type($robot_schedule_type)){
				$robot_schedule_model->type = $robot_schedule_type;
			}
		}

		if(Yii::app()->params['schedule_enforce_versioning']){
			if($robot_schedule_xml_data_version && $robot_schedule_xml_data_version != $robot_schedule_xml_data_latest_version){
				$response_message = self::yii_api_echo('Version mismatch for schedule xml data.');
				self::terminate(-1, $response_message, APIConstant::DOES_NOT_MATCH_LATEST_XML_DATA_VERSION);
			}

			if($robot_schedule_blob_data_version && $robot_schedule_blob_data_version != $robot_schedule_blob_data_latest_version){
				$response_message = self::yii_api_echo('Version mismatch for schedule blob data.');
				self::terminate(-1, $response_message, APIConstant::DOES_NOT_MATCH_LATEST_BLOB_DATA_VERSION);
			}
		}

		if($robot_schedule_xml_data_version || $robot_schedule_blob_data_version){

			$old_schedule_xml_data_file_path = '';
			$old_schedule_blob_data_file_path = '';

			if ($robot_schedule_xml_data_version){
				$xml_data = Yii::app()->request->getParam('xml_data', '');

				if(!AppCore::validate_schedule_xml_data($xml_data)){
					$response_message = self::yii_api_echo('Invalid xml data.');
					self::terminate(-1, $response_message, APIConstant::INVALID_XML);
				}
				//storing xml data

				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_id;

				$uploads_dir = $uploads_dir_for_robot_schedule . DIRECTORY_SEPARATOR . Yii::app()->params['robot-schedule_xml-data-directory-name'];

				if (!is_dir($uploads_dir)) {
					mkdir($uploads_dir);
				}

				$schedule_xml_data_file_name = time(). '.xml';
				$full_file_path_schedule_xml_data = $uploads_dir. DIRECTORY_SEPARATOR . $schedule_xml_data_file_name;

				if($robot_schedule_model->xml_data_file_name != ''){
					$old_schedule_xml_data_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $robot_schedule_model->xml_data_file_name;
				}

				$schedule_xml_file_handle = fopen($full_file_path_schedule_xml_data, 'w');
				fwrite($schedule_xml_file_handle,$xml_data);//@todo need to handle file write exceptions
				fclose($schedule_xml_file_handle);

				$schedule_xml_data_file_version = $robot_schedule_xml_data_latest_version + 1;
				$robot_schedule_model->xml_data_file_name = $schedule_xml_data_file_name;

				$robot_schedule_xml_data_model = new RobotScheduleXmlDataVersion();
				$robot_schedule_xml_data_model->id_robot_schedule = $robot_schedule_id;
				$robot_schedule_xml_data_model->version = $schedule_xml_data_file_version;
				if(!$robot_schedule_xml_data_model->save()){
					//need to work
				}
			}

			//storing blob data
			if ($robot_schedule_blob_data_version){
				$schedule_blob_data_file_name = '';

				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_id;

				$uploads_dir = $uploads_dir_for_robot_schedule . DIRECTORY_SEPARATOR . Yii::app()->params['robot-schedule_blob-data-directory-name'];

				if($encoded_blob_data !== ''){
					$decoded_blob_data = base64_decode($encoded_blob_data);
					$f = finfo_open();
					$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
					finfo_close($f);
					$schedule_blob_data_file_extension = 'jpg';
					if(strpos($mime_type, "image")!== false){
						$schedule_blob_data_file_extension = str_replace("image/","",$mime_type);
					}

					$schedule_blob_data_file_name = time(). "." .$schedule_blob_data_file_extension;
					$blob_data = $decoded_blob_data;

					// Add check to see if the folder already exists
					if(!is_dir($uploads_dir)){
						mkdir($uploads_dir);
					}
					$full_file_path_schedule_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $schedule_blob_data_file_name;

					$schedule_blob_file_handle = fopen($full_file_path_schedule_blob_data, 'w');
					fwrite($schedule_blob_file_handle, $blob_data);//@todo need to handle file write exceptions
					fclose($schedule_blob_file_handle);
				}
				elseif(isset($_FILES['blob_data'])){
					$schedule_blob_data_temp_file_path = $_FILES['blob_data']['tmp_name'];
					$schedule_blob_data_file_extension = pathinfo($_FILES['blob_data']['name'], PATHINFO_EXTENSION);
					$schedule_blob_data_file_name = time(). "." .$schedule_blob_data_file_extension;

					$temp_file_handle = fopen($schedule_blob_data_temp_file_path, "r");
					$blob_data = fread($temp_file_handle, filesize($schedule_blob_data_temp_file_path));
					fclose($temp_file_handle);

					// Add check to see if the folder already exists
					if(!is_dir($uploads_dir)){
						mkdir($uploads_dir);
					}
					$full_file_path_schedule_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $schedule_blob_data_file_name;

					$schedule_blob_file_handle = fopen($full_file_path_schedule_blob_data, 'w');
					fwrite($schedule_blob_file_handle, $blob_data);//@todo need to handle file write exceptions
					fclose($schedule_blob_file_handle);
				}

				if($robot_schedule_model->blob_data_file_name != ''){
					$old_schedule_blob_data_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $robot_schedule_model->blob_data_file_name;
				}

				$schedule_blob_data_file_version = $robot_schedule_blob_data_latest_version + 1;
				$robot_schedule_model->blob_data_file_name = $schedule_blob_data_file_name;

				$robot_schedule_blob_data_model = new RobotScheduleBlobDataVersion();
				$robot_schedule_blob_data_model->id_robot_schedule = $robot_schedule_id;
				$robot_schedule_blob_data_model->version = $schedule_blob_data_file_version;
				if(!$robot_schedule_blob_data_model->save()){
					//TODO
				}
			}

			$robot_schedule_model->updated_on = date("Y-m-d H:i:s");

			if($robot_schedule_model->update()){
				if($old_schedule_xml_data_file_path != ''){
					unlink($old_schedule_xml_data_file_path);
				}
				if($old_schedule_blob_data_file_path != ''){
					unlink($old_schedule_blob_data_file_path);
				}

				$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully updated robot schedule data.'), 'schedule_version' => $robot_schedule_model->XMLDataLatestVersion);
				self::success($response_data);
			}
		}else{
			$response_message = self::yii_api_echo('Provide at least one data version(xml or blob) or schedule type.');
			self::terminate(-1, $response_message, APIConstant::MISSING_BOTH_DATA_VERSIONS);
		}

	}

	/**
	 * API to get robot schedules
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
	 *				{"status":0,"result":[{"id":"1","schedule_type":"Advanced","xml_data_version":"1","blob_data_version":"1"},{"id":"6","schedule_type":"Basic","xml_data_version":"1","blob_data_version":"1"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and schedule does not exist
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
	 *	<li>If a serial number is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robotschedule.get_schedules"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetSchedules(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot_model = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$robot_schedule_arr = array();
		foreach ($robot_model->robotSchedules as $robot_schedule){
			$robot_schedule_details = array();
			$robot_schedule_details['id'] = $robot_schedule->id;
			$robot_schedule_details['schedule_type'] = $robot_schedule->type;

			$robot_schedule_details['xml_data_version'] = $robot_schedule->XMLDataLatestVersion;
			$robot_schedule_details['blob_data_version'] = $robot_schedule->BlobDataLatestVersion;

			$robot_schedule_arr[] = $robot_schedule_details;
		}
		self::success($robot_schedule_arr);
	}

	/**
	 * API to get robot schedule data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_schedule_id</b> :Robot Schedule Id</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"schedule_type":"Basic","xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353061075.xml","blob_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/blob\/Koala.jpg"}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and blob data file is not exist
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"schedule_type":"Advanced","xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_data\/34\/xml\/1353397443.xml","blob_data_url":""}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot schedule id is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot schedule id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_schedule_id
	 *				in method robotschedule.get_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetData(){
		$robot_schedule_id = Yii::app()->request->getParam('robot_schedule_id', '');
		$robot_schedule_model = self::verify_for_robot_schedule_id_existence($robot_schedule_id);

		$response_data = array("schedule_type" => $robot_schedule_model->type ,"xml_data_url" => $robot_schedule_model->XMLDataURL,
				"blob_data_url" => $robot_schedule_model->BlobDataURL);
		self::success($response_data);
	}

	/**
	 * API to delete robot schedule data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_schedule_id</b> :Robot Schedule Id</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully deleted robot schedule data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot schedule id is not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot schedule id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_schedule_id in method robotschedule.delete_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionDeleteScheduleData(){
		$robot_schedule_id = Yii::app()->request->getParam('robot_schedule_id', '');
		$robot_schedule_model = self::verify_for_robot_schedule_id_existence($robot_schedule_id);

		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_id;

		if($robot_schedule_model->delete()){
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot_schedule);

			$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully deleted robot schedule data.'));
			self::success($response_data);
		}

	}

	/**
	 * Deletes a particular robot schedule.
	 * It is called by ajax call.
	 */
	public function actiondeleteSchedule()
	{
		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$robot_schedule_model = RobotSchedule::model()->findByPk($id);

		if(RobotSchedule::model()->deleteByPk($id)){

			$robot_id = $robot_schedule_model->id_robot;

			$robot = Robot::model()->find('id = :id', array(':id' => $robot_id));

			$key = Yii::app()->params['schedule_key'];
			$value = Yii::app()->params['schedule_value'];

			$utc_str = gmdate("M d Y H:i:s", time());
			$utc = strtotime($utc_str);

			RobotCore::setRobotKeyValueDetail($robot, $key, $value, $utc);

			$user_id = Yii::app()->user->id;
			$user_data = User::model()->findByPk($user_id);
			$cause_agent_id = Yii::app()->session['cause_agent_id'];
			$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);

			RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value);

			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $id;
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot_schedule);
			$content = array('status' => 0);
		}else{
			$content = array('status' => -1);
		}

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	public function actionAdd() {

		if (!isset($_FILES['RobotSchedule']) ||
				(!(isset($_FILES['RobotSchedule']['tmp_name']['xml_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['xml_data_file_name'])) &&
						!(isset($_FILES['RobotSchedule']['tmp_name']['blob_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['blob_data_file_name']))
				)) {
			$response_message = self::yii_api_echo('Provide at least one data (xml or blob).');
			self::terminate(-1, $response_message, APIConstant::MISSING_BOTH_DATA_VERSIONS);
		}

		$xml_data = "";
		$encoded_blob_data = "";
		if (isset($_FILES['RobotSchedule']['tmp_name']['xml_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['xml_data_file_name'])) {
			$xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['xml_data_file_name'];
			$handle = fopen($xml_data_temp_file_path, "r");
			$xml_data = fread($handle, filesize($xml_data_temp_file_path));
			fclose($handle);
		} else {
			unset($_POST['xml_data_version']);
		}
		if (isset($_FILES['RobotSchedule']['tmp_name']['blob_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['blob_data_file_name'])) {
			$blob_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['blob_data_file_name'];
			$handle = fopen($blob_data_temp_file_path, "r");
			$original_content = fread($handle, filesize($blob_data_temp_file_path));
			fclose($handle);
			$encoded_blob_data = base64_encode($original_content);
		} else {
			unset($_POST['blob_data_version']);
		}
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);

		if (count($robot->robotSchedules) > 0) {

			$_POST['robot_schedule_id'] = $robot->robotSchedules[0]->id;

			$_POST['xml_data_version'] = $robot->robotSchedules[0]->robotScheduleXmlDataVersions[0]->version;
			$_POST['blob_data_version'] = $robot->robotSchedules[0]->robotScheduleBlobDataVersions[0]->version;

			$_POST['yt0'] = 'Save';

			self::actionUpdate();
		} else {

			$_POST['schedule_type'] = $_POST['RobotSchedule']['type'];
			$_POST['xml_data'] = $xml_data;
			$_POST['encoded_blob_data'] = $encoded_blob_data;

			self::actionPostData();
		}
		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	public function actionUpdate(){
		if(!isset($_FILES['RobotSchedule'])||
				(! (isset($_FILES['RobotSchedule']['tmp_name']['xml_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['xml_data_file_name'])) &&
						! (isset($_FILES['RobotSchedule']['tmp_name']['blob_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['blob_data_file_name']))
				)){
			$response_message = self::yii_api_echo('Provide at least one data (xml or blob).');
			self::terminate(-1, $response_message, APIConstant::MISSING_BOTH_DATA_VERSIONS);
		}

		$xml_data = "";
		$encoded_blob_data = "";
		if(isset($_FILES['RobotSchedule']['tmp_name']['xml_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['xml_data_file_name'])){
			$xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['xml_data_file_name'];
			$handle = fopen($xml_data_temp_file_path, "r");
			$xml_data = fread($handle, filesize($xml_data_temp_file_path));
			fclose($handle);
		}else{
			unset($_POST['xml_data_version']);
		}

		if(isset($_FILES['RobotSchedule']['tmp_name']['blob_data_file_name']) && file_exists($xml_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['blob_data_file_name'])){
			$blob_data_temp_file_path = $_FILES['RobotSchedule']['tmp_name']['blob_data_file_name'];
			$handle = fopen($blob_data_temp_file_path, "r");
			$original_content = fread($handle, filesize($blob_data_temp_file_path));
			fclose($handle);
			$encoded_blob_data = base64_encode($original_content);
		}else{
			unset($_POST['blob_data_version']);
		}
		$_POST['schedule_type'] = $_POST['RobotSchedule']['type'];;
		$_POST['xml_data'] = $xml_data;
		$_POST['encoded_blob_data'] = $encoded_blob_data;

		self::actionUpdateData();

		$this->renderPartial('/default/defaultView', array('content' => $content));

	}

	public function actionGetScheduleBasedOnType() {

		$robot_serial_number = Yii::app()->request->getParam('robot_serial_number', '');
		$schedule_type = Yii::app()->request->getParam('schedule_type', '');

		if($schedule_type == '1'){
			$schedule_type = 'Basic';
		} else if($schedule_type == '2'){
			$schedule_type = 'Advanced';
		}

		$robot_model = self::verify_for_robot_serial_number_existence($robot_serial_number);

		$robot_schedule_arr = array();

		foreach ($robot_model->robotSchedules as $robot_schedule) {

			if($schedule_type == $robot_schedule->type){

				$robot_schedule_details = array();
				$robot_schedule_details['schedule_id'] = $robot_schedule->id;
				$robot_schedule_details['schedule_type'] = $robot_schedule->type;

				$robot_schedule_details['schedule_version'] = $robot_schedule->XMLDataLatestVersion;

				if(AppHelper::remote_file_exists($robot_schedule->XMLDataURL)){

					$fileContents = file_get_contents($robot_schedule->XMLDataURL);
					$robot_schedule_details['schedule_data'] = str_replace(array("\n", "\r", "\t"), '', $fileContents);

				} else {
					$robot_schedule_details['schedule_data'] = false;
				}

				$robot_schedule_arr[] = $robot_schedule_details;

			}

		}

		if(empty($robot_schedule_arr)){
			self::terminate(-1, "Sorry, we didn't find any schedule data for given robot serial number and schedule type", APIConstant::NO_SCHEDULE_DATA_FOUND);
		}
		self::success($robot_schedule_arr);
	}

	public function actionSetKeyValueAndSendXMPP(){

		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');

		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);

		$content = array('code'=> 1);

		if(!empty($robot)){

			$key = Yii::app()->params['schedule_key'];
			$value = Yii::app()->params['schedule_value'];

			$utc_str = gmdate("M d Y H:i:s", time());
			$utc = strtotime($utc_str);

			RobotCore::setRobotKeyValueDetail($robot, $key, $value, $utc);

			$user_id = Yii::app()->user->id;
			$user_data = User::model()->findByPk($user_id);
			$cause_agent_id = Yii::app()->session['cause_agent_id'];
			$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);

			RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value);

			$content = array('code'=> 0);

		}

		$this->renderPartial('/default/defaultView', array('content' => $content));

	}

}