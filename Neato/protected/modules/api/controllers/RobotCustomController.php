<?php

/**
 * The API RobotCustomController is meant for all robot-custom related API actions.
 */
class RobotCustomController extends APIController {

	/**
	 * Information about the custom data type id.
	 *
	 * @param  $custom_data_type_name
	 * @return int
	 */
	protected function get_custom_data_type_id($custom_data_type_name){
		$custom_data_type_model = RobotCustomDataType::model()->findByAttributes(array('name' => $custom_data_type_name));
		if($custom_data_type_model !== null ){
			return $custom_data_type_model->id;
		}else{
			$custom_data_type_model = new RobotCustomDataType();
			$custom_data_type_model->name = $custom_data_type_name;
			$custom_data_type_model->save();
			return $custom_data_type_model->id;
		}
	}

	/**
	 * It coverts into an array
	 *
	 * @param $blob_data_arrays
	 * @return array
	 */
	protected function blob_data_array_convert($blob_data_arrays){
		$blob_data_array = array();
		foreach($blob_data_arrays as $key1 => $value1)
			foreach($value1 as $key2 => $value2)
			$blob_data_array[$key2][$key1] = $value2;
		return $blob_data_array;
	}

	/**
	 *API to add new custom data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *	<li><b>encoded_blob_data[]</b> :Array of Custom Data of
	 *		key=>value pairs, e.g. encoded_blob_data{'history'=>'encoded
	 *		data', 'recent'=>'encoded data'}.The key is the type and value
	 *		is in base 64 encoded string.You can generate base 64 encoded
	 *		string for a file using this <a href='robot_data_encode.php'
	 *		target='_blank'>link</a>
	 *	</li>
	 *	<li><b>blob_data[]</b> :Array of Custom Data of key=>value pairs,
	 *		e.g. blob_data{'history'=>'robot.jpg', 'recent'=>'room.xml'}</li>
	 *</ul>
	 *Scenarios
	 *<ul>
	 *	<li>If keys and only blob_data[] is provided
	 *		<ul>
	 *			<li>It would create blob file with provided blob_data[] file
	 *				extension.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If keys and only encoded_blob_data[] is provided
	 *		<ul>
	 *			<li>It would create blob file with provided
	 *				encoded_blob_data[].</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If keys, blob_data[] and encoded_blob_data[] are provided
	 *		<ul>
	 *			<li>It would create blob file with provided
	 *				encoded_blob_data[].</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If encoded_blob_data[] is provided
	 *		<ul>
	 *			<li>Blob data check for file mime type,
	 *				<ul>
	 *					<li>if file mime type is image it will check for file
	 *extension (jpg/jpeg/gif/png)</li>
	 *				</ul>
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>The encoded_blob_data[] and blob_data[] files to be supported
	 *		<ul>
	 *			<li>Only jpg/jpeg/gif/png files are supported by custom data</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"robot_custom_id":"2","history":1,"recent":1}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If serial number provided but keys not provided.
	 *		<ul>
	 *			<li>{"status":-1,"message":"Provide atleast one data"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If serial number and key provided but both blob_data[] and
	 *		encoded_blob_data[] are not provided.
	 *		<ul>
	 *			<li>{"status":-1,"message":"Provide atleast one data"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robot.post_custom_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *
	 */
	public function actionPostData(){

		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$robot_id = $robot->id;
		
		$encoded_blob_data_arr = Yii::app()->request->getParam('encoded_blob_data', '');
                if (!$encoded_blob_data_arr || !isset($_FILES['blob_data'])) {
			if (!$encoded_blob_data_arr){
				$response_message = self::yii_api_echo('Provide atleast one data.');
				self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
			}
			foreach ($encoded_blob_data_arr as $key=>$value){
				$is_encode_blob_data_exist = false;
				if($value == '' ){
					$is_encode_blob_data_exist = true;
				}else{
					break;
				}
			}
			if ($is_encode_blob_data_exist){
				$response_message = self::yii_api_echo('Provide atleast one data.');
				self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
			}
		}
                
                if (isset($_FILES['blob_data'])){
			$blob_data_array = self::blob_data_array_convert($_FILES["blob_data"]);
                        
                    foreach ($blob_data_array as $key => $value){
                        $image_type = $value['type'];
                        $imagefile_extn = explode("/", $image_type);
                        $image_format = isset($imagefile_extn[1]) ? $imagefile_extn[1] : '';
                        }
		}
                else{
                    $response_message = self::yii_api_echo('Only jpg/jpeg/gif/png files are supported by custom data');
                    self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_FILE_TYPE);
                }
                
		$encoded_blob_data_type_arr = array();
		$suported_extension_arr =  array('jpg','jpeg', 'gif', 'png');

		foreach ($encoded_blob_data_arr as $key=>$encoded_blob_data){
			if($encoded_blob_data !== ''){
				$encoded_blob_data_type_arr[] = $key;

				$decoded_blob_data = base64_decode($encoded_blob_data);
				$f = finfo_open();
				$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
				finfo_close($f);

				$custom_blob_data_file_extension = 'none';
				if(strpos($mime_type, "image")!== false){
					$custom_blob_data_file_extension = str_replace("image/","",$mime_type);
				}
                                
				if(!in_array($image_format, $suported_extension_arr)){
					$response_message = self::yii_api_echo('Only jpg/jpeg/gif/png files are supported by custom data');
					self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_FILE_TYPE);
				}
			}
		}
		if (isset($_FILES['blob_data'])){
			$blob_data_array = self::blob_data_array_convert($_FILES["blob_data"]);
			foreach ($blob_data_array as $key => $value){
				if (!in_array($key, $encoded_blob_data_type_arr)){
					$custom_blob_data_temp_file_path = $value['tmp_name'];
					$custom_blob_data_file_extension = pathinfo($value['name'], PATHINFO_EXTENSION);

					if(!in_array($custom_blob_data_file_extension, $suported_extension_arr)){
						$response_message = self::yii_api_echo('Only jpg/jpeg/gif/png files are supported by custom data');
						self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_FILE_TYPE);
					}
				}
			}
		}

		$robot_custom_model = new RobotCustom();
		$robot_custom_model->id_robot = $robot_id;

		if(!$robot_custom_model->save()){
			//need to work
		}

		$robot_custom_post_data_details = array();

		$custom_blob_data_file_version = 1;
		$custom_blob_data_file_name = '';
		$uploads_dir_for_robot_custom = '';

		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot_custom = Yii::app()->getBasePath().$back . Yii::app()->params['robot-custom-data-directory-name']. DIRECTORY_SEPARATOR . $robot_custom_model->id;
		// Add check to see if the folder already exists
		if(!is_dir($uploads_dir_for_robot_custom)){
			mkdir($uploads_dir_for_robot_custom);
		}

		$encoded_blob_data_type_arr = array();
			
		foreach ($encoded_blob_data_arr as $key=>$encoded_blob_data){

			if($encoded_blob_data !== ''){
				$encoded_blob_data_type_arr[] = $key;

				$robot_custom_post_data_details[$key] = $custom_blob_data_file_version;

				$decoded_blob_data = base64_decode($encoded_blob_data);
				$f = finfo_open();
				$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
				finfo_close($f);
				$custom_blob_data_file_extension = 'jpg';
				if(strpos($mime_type, "image")!== false){
					$custom_blob_data_file_extension = str_replace("image/","",$mime_type);
				}

				$custom_blob_data_file_name = time().'_'.$key. "." .$custom_blob_data_file_extension;
				$blob_data = $decoded_blob_data;

				$full_file_path_custom_blob_data = $uploads_dir_for_robot_custom. DIRECTORY_SEPARATOR . $custom_blob_data_file_name;

				$custom_blob_file_handle = fopen($full_file_path_custom_blob_data, 'w');
				fwrite($custom_blob_file_handle, $blob_data); //@todo need to handle file write exceptions
				fclose($custom_blob_file_handle);

				$robot_custom_data_model = new RobotCustomData();
				$robot_custom_data_model->id_robot_custom = $robot_custom_model->id;
				$robot_custom_data_model->id_robot_custom_data_type = self::get_custom_data_type_id($key);
				$robot_custom_data_model->file_name = $custom_blob_data_file_name;
				$robot_custom_data_model->version = $custom_blob_data_file_version;

				if(!$robot_custom_data_model->save()){
					//need to work
				}
			}
		}
		if (isset($_FILES['blob_data'])){
			$blob_data_array = self::blob_data_array_convert($_FILES["blob_data"]);
			foreach ($blob_data_array as $key => $value){

				if (!in_array($key, $encoded_blob_data_type_arr)){

					$robot_custom_post_data_details[$key] = $custom_blob_data_file_version;

					$custom_blob_data_temp_file_path = $value['tmp_name'];
					$custom_blob_data_file_extension = pathinfo($value['name'], PATHINFO_EXTENSION);
					$custom_blob_data_file_name = time().'_'.$key. "." .$custom_blob_data_file_extension;

					$handle = fopen($custom_blob_data_temp_file_path, "r");
					$blob_data = fread($handle, filesize($custom_blob_data_temp_file_path));
					fclose($handle);

					$full_file_path_custom_blob_data = $uploads_dir_for_robot_custom. DIRECTORY_SEPARATOR . $custom_blob_data_file_name;

					$custom_blob_file_handle = fopen($full_file_path_custom_blob_data, 'w');
					fwrite($custom_blob_file_handle, $blob_data); //@todo need to handle file write exceptions
					fclose($custom_blob_file_handle);

					$robot_custom_data_model = new RobotCustomData();
					$robot_custom_data_model->id_robot_custom = $robot_custom_model->id;
					$robot_custom_data_model->id_robot_custom_data_type = self::get_custom_data_type_id($key);
					$robot_custom_data_model->file_name = $custom_blob_data_file_name;
					$robot_custom_data_model->version = $custom_blob_data_file_version;

					if(!$robot_custom_data_model->save()){
						//need to work
					}
				}
			}
		}
		$response_data = array_merge(array("success"=>true, "robot_custom_id"=>$robot_custom_model->id), $robot_custom_post_data_details);
		self::success($response_data);
	}

	/**
	 * API to get robot customs
	 *
	 * Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":[{"id":"9","history":"1","recent":"1","image":"1"},{"id":"10","img":"1"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and custom does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":[]}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":""Serial number does not exist""}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robot.get_customs"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *
	 */
	public function actionGetCustoms(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot_model = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$robot_custom_arr = array();
		foreach ($robot_model->robotCustoms as $robot_custom){
			$robot_custom_details = array();
			$robot_custom_details['id'] = $robot_custom->id;
			foreach ($robot_custom->robotCustomData as $robot_custom_data){
				$robot_custom_details[$robot_custom_data->idRobotCustomDataType->name] = $robot_custom_data->version;
			}
			$robot_custom_arr[] = $robot_custom_details;
		}
		self::success($robot_custom_arr);
	}

	/**
	 * API to get robot custom data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_custom_id</b> :Robot Custom Id</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":[{"recent":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_custom_data\/8\/1354636168_recent.jpg"},{"image":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_custom_data\/8\/1354636168_image.jpg"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot custom id does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot custom id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_custom_id
	 *				in method robot.get_custom_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetData(){
		$robot_custom_id = Yii::app()->request->getParam('robot_custom_id', '');
		$robot_custom_model = self::verify_for_robot_custom_id_existence($robot_custom_id);
		$robot_custom_arr = array();
		foreach ($robot_custom_model->robotCustomData as $robot_custom_data){
			$robot_custom_details = array();
			$robot_custom_details[$robot_custom_data->idRobotCustomDataType->name] = $robot_custom_data->DataURL;
			$robot_custom_arr[] = $robot_custom_details;
		}
		self::success($robot_custom_arr);
	}

	/**
	 * API to update custom data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_custom_id</b> :Robot Custom Id</li>
	 *	<li><b>blob_data_version[]</b> :Array of Data Version of
	 *		key=>value pairs,e.g data_version{'history'=>'1', 'recent'=>'1'}</li>
	 *	<li><b>encoded_blob_data[]</b> :(Optional)Array of Custom Data of
	 *		key=>value pairs, e.g. encoded_blob_data{'history'=>'encoded
	 *		data', 'recent'=>'encoded data'}.The key is the type and value
	 *		is in base 64 encoded string.You can generate base 64 encoded
	 *		string for a file using this <a href='robot_data_encode.php'
	 *		target='_blank'>link</a>
	 *	</li>
	 *	<li><b>blob_data[]</b> :(Optional)Array of Custom Data of
	 *		key=>value pairs, e.g. blob_data{'history'=>'robot.jpg',
	 *		'recent'=>'room.xml'}</li>
	 *</ul>
	 *Scenarios
	 *<ul>
	 *	<li>If blob_data_version[] provided and both encoded_blob_data[]
	 *		and blob_data[] are not provided
	 *		<ul>
	 *			<li>It would delete previous blob data file</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data_version[] provided and only blob_data[] is
	 *		provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob
	 *				file with provided blob_data[] file.</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data_version[] provided and both encoded_blob_data[]
	 *		and blob_data[] are provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob
	 *				file with provided encoded_blob_data[].</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data_version[] provided and only encoded_blob_data[]
	 *		are provided
	 *		<ul>
	 *			<li>It would delete previous blob data file and create blob
	 *				file with provided encoded_blob_data[].</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If encoded_blob_data[] is provided
	 *		<ul>
	 *			<li>Blob data check for file mime type,
	 *				<ul>
	 *					<li>if file mime type is image it will check for file
	 **extension (jpg/jpeg/gif/png)</li>
	 *				</ul>
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>The encoded_blob_data[] and blob_data[] files to be supported
	 *		<ul>
	 *			<li>Only jpg/jpeg/gif/png files are supported by custom data</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If blob_data_version[] provided and goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot custom data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data_version[] and encoded_blob_data[] are provided
	 *		everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot custom data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data_version[] and blob_data[] are provided
	 *		everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot custom data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *<ul>
	 *	<li>If blob_data_version[], encoded_blob_data[] and blob_data[]
	 *		are provided everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully updated robot custom data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot custom id does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot custom id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If custom id provided but keys not provided.
	 *		<ul>
	 *			<li>{"status":-1,"message":"Provide atleast one data and
	 *				version."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If custom id and keys provided but
	 *		blob_data_version[],blob_data[] and encoded_blob_data[] are not
	 *		provided.
	 *		<ul>
	 *			<li>{"status":-1,"message":"Provide atleast one data and
	 *				version."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If custom id ,keys and blob_data_version[] are provided but
	 *		blob_data_version[] not matching with latest blob_data_version.
	 *		<ul>
	 *			<li>{"status":-1,"message":"Version mismatch for (key_name)"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If custom id and keys and blob_data_version[] provided but
	 *		keys are not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"(key_name) not found."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_custom_id
	 *				in method robot.update_custom_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionUpdateData(){

		$robot_custom_id = Yii::app()->request->getParam('robot_custom_id', '');
		$robot_custom_model = self::verify_for_robot_custom_id_existence($robot_custom_id);

		$blob_data_version_arr = Yii::app()->request->getParam('blob_data_version', '');
		if ($blob_data_version_arr){
			foreach ($blob_data_version_arr as $key=>$blob_data_version){
				$latest_version = $robot_custom_model->getBlobDataLatestVersion($key);
				if (!$latest_version){
					$response_message = self::yii_api_echo("$key not found.");
					self::terminate(-1, $response_message, APIConstant::DOES_NOT_MATCH_LATEST_XML_DATA_VERSION);
				}elseif($latest_version !== $blob_data_version){
					$response_message = self::yii_api_echo("Version mismatch for $key.");
					self::terminate(-1, $response_message, APIConstant::DOES_NOT_MATCH_LATEST_BLOB_DATA_VERSION);
				}
			}
		}else{
			$response_message = self::yii_api_echo('Provide atleast one data and version.');
			self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
		}

		$encoded_blob_data_arr = Yii::app()->request->getParam('encoded_blob_data', '');
		$blob_data_array = array();
		if (isset($_FILES['blob_data'])){
			$blob_data_array = self::blob_data_array_convert($_FILES["blob_data"]);
                        
                    foreach ($blob_data_array as $key => $value){
                        $image_type = $value['type'];
                        $imagefile_extn = explode("/", $image_type);
                        $image_format = isset($imagefile_extn[1]) ? $imagefile_extn[1] : '';
                        }
		}
                else{
                    $response_message = self::yii_api_echo('Only jpg/jpeg/gif/png files are supported by custom data');
                    self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_FILE_TYPE);
                }
              
                $encoded_blob_data_type_arr = array();
		$suported_extension_arr =  array('jpg','jpeg', 'gif', 'png');
                
		foreach ($encoded_blob_data_arr as $key=>$encoded_blob_data){
			if($encoded_blob_data !== ''){
				$encoded_blob_data_type_arr[] = $key;
                                
				$decoded_blob_data = base64_decode($encoded_blob_data);
				$f = finfo_open();
				$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
                                finfo_close($f);

				$custom_blob_data_file_extension = 'none';
				if(strpos($mime_type, "image")!== false){
					$custom_blob_data_file_extension = str_replace("image/","",$mime_type);
				}
                        
				if(!in_array($image_format, $suported_extension_arr)){
					$response_message = self::yii_api_echo('Only jpg/jpeg/gif/png files are supported by custom data');
					self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_FILE_TYPE);
                                }
			}
		}
		if (isset($_FILES['blob_data'])){
			foreach ($blob_data_array as $key => $value){
				if (!in_array($key, $encoded_blob_data_type_arr)){
					$custom_blob_data_temp_file_path = $value['tmp_name'];
					$custom_blob_data_file_extension = pathinfo($value['name'], PATHINFO_EXTENSION);
                                        
					if(!in_array($custom_blob_data_file_extension, $suported_extension_arr)){
						$response_message = self::yii_api_echo('Only jpg/jpeg/gif/png files are supported by custom data');
						self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_FILE_TYPE);
					}
				}
			}
		}

		$uploads_dir_for_robot_custom = '';
                
		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot_custom = Yii::app()->getBasePath().$back . Yii::app()->params['robot-custom-data-directory-name']. DIRECTORY_SEPARATOR . $robot_custom_model->id;

		foreach ($blob_data_version_arr as $key=>$blob_data_version){
			$custom_blob_data_file_name = '';
			$new_blob_data_file_path = '';
			$old_blob_data_file_path = '';
			if(isset($encoded_blob_data_arr[$key]) && $encoded_blob_data_arr[$key] !== ''){
				//encodedblob data update
				$decoded_blob_data = base64_decode($encoded_blob_data_arr[$key]);
				$f = finfo_open();
				$mime_type = finfo_buffer($f, $decoded_blob_data, FILEINFO_MIME_TYPE);
                                finfo_close($f);
				$custom_blob_data_file_extension = 'jpg';
				if(strpos($mime_type, "image")!== false){
					$custom_blob_data_file_extension = str_replace("image/","",$mime_type);
				}

				$custom_blob_data_file_name = time().'_'.$key. "." .$custom_blob_data_file_extension;
                                $blob_data = $decoded_blob_data;

				$new_blob_data_file_path = $uploads_dir_for_robot_custom. DIRECTORY_SEPARATOR . $custom_blob_data_file_name;

			} elseif(isset($blob_data_array[$key])){
				//file data updatae
				$bolob_data = $blob_data_array[$key];
				$custom_blob_data_temp_file_path = $bolob_data['tmp_name'];
				$custom_blob_data_file_extension = pathinfo($bolob_data['name'], PATHINFO_EXTENSION);
					
				$custom_blob_data_file_name = time().'_'.$key. "." .$custom_blob_data_file_extension;

				$handle = fopen($custom_blob_data_temp_file_path, "r");
				$blob_data = fread($handle, filesize($custom_blob_data_temp_file_path));
				fclose($handle);

				$new_blob_data_file_path = $uploads_dir_for_robot_custom. DIRECTORY_SEPARATOR . $custom_blob_data_file_name;
			}

			//file writing
			if ($new_blob_data_file_path != ''){
				$custom_blob_file_handle = fopen($new_blob_data_file_path, 'w');
				fwrite($custom_blob_file_handle, $blob_data); //@todo need to handle file write exceptions
                                fclose($custom_blob_file_handle);
			}

			//update robot_custom_data
			$id_robot_custom_data_type = self::get_custom_data_type_id($key);
			$robot_custom_data_model = RobotCustomData::model()->findByAttributes(array('id_robot_custom' => $robot_custom_id,'id_robot_custom_data_type' =>$id_robot_custom_data_type));

			if($robot_custom_data_model->file_name != ''){
				$old_blob_data_file_path = $uploads_dir_for_robot_custom. DIRECTORY_SEPARATOR .$robot_custom_data_model->file_name;
                        }

			$robot_custom_data_model->id_robot_custom = $robot_custom_id;
			$robot_custom_data_model->id_robot_custom_data_type = $id_robot_custom_data_type;
			$robot_custom_data_model->file_name = $custom_blob_data_file_name;
			$robot_custom_data_model->version = $blob_data_version + 1;

			if($robot_custom_data_model->update()){
				if($old_blob_data_file_path != ''){
					unlink($old_blob_data_file_path);
				}
			}else{
				//need to work
			}
		}
		$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully updated robot custom data.'));
		self::success($response_data);
	}

	/**
	 * API to delete robot custom data
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>robot_custom_id</b> :Robot Custom Id</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You have
	 *				successfully deleted robot custom data."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If robot custom id does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot custom id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter robot_custom_id
	 *				in method robot.delete_custom_data"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionDeleteData(){
		$robot_custom_id = Yii::app()->request->getParam('robot_custom_id', '');
		$robot_custom_model = self::verify_for_robot_custom_id_existence($robot_custom_id);

		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-custom-data-directory-name']. DIRECTORY_SEPARATOR . $robot_custom_model->id;
		if($robot_custom_model->delete()){
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot);
			$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully deleted robot custom data.'));
			self::success($response_data);
		}

	}

}