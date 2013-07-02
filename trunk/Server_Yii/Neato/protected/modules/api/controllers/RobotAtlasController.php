<?php

/**
 * The API RobotAtlasController is meant for all robot-atlas related API actions.
 */
class RobotAtlasController extends APIController {

	/**
	 * Method to add atlas data for robot of given serial number.
	 * 
     * Parameters
     * 	<ul>
     *		<li><b>serial_number</b> :Serial number of robot</li>
     *		<li><b>xml_data</b> :XML data for robot atlas</li>
     *	</ul>
     *	Success Response:
     *	<ul>
     *		<li>If everything goes fine
     *			<ul>
     *				<li>
     *					{"status":0,"result":{"success":true,"robot_atlas_id":"4","xml_data_version":1,"message":"You have successfully added Robot Atlas","atlas_id":"4"}}
     *				</li>
     *			</ul>
     *		</li>
     *	</ul>
     *
     *	Failure Responses: <br />
     *	<ul>
     *		<li>If serial number does not exists
     *			<ul>
     *				<li>{"status":-1,"message":"Serial number does not exist"}</li>
     *			</ul>
     *		</li>
     *		<li>If a serial number is missing
     *			<ul>
     *				<li>{"status":-1,"message":"Missing parameter serial_number in
     *					method robot.add_atlas"}</li>
     *			</ul>
     *		</li>
     *		<li>If XML data is missing
     *			<ul>
     *				<li>{"status":-1,"message":"Missing parameter xml_data in
     *					method robot.add_atlas"}</li>
     *			</ul>
     *		</li>
     *		<li>If atlas already added
	 *			<ul>
	 *				<li>{"status":-1,"message":"Robot can have only one atlas"}</li>
	 *			</ul>
	 *		</li>
     *		
     *	</ul>
	 */
	public function actionPostAtlas(){
		
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_atlas_repetition($robot_serial_no);
		
		$robot_id = $robot->id;
		$xml_data = Yii::app()->request->getParam('xml_data', '');
		
		if(!AppCore::validate_atlas_xml_data($xml_data)){
			$response_message = self::yii_api_echo('Invalid xml data.');
			self::terminate(-1, $response_message, APIConstant::INVALID_XML);
		}

		$robot_atlas_model = new RobotAtlas();
		$robot_atlas_model->id_robot = $robot_id;
		if(!$robot_atlas_model->save()){
			//need to work
		}

		//storing xml data
		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $robot->id;
		// Add check to see if the folder already exists
		if(!is_dir($uploads_dir_for_robot)){
			mkdir($uploads_dir_for_robot);
		}
		$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-atlas-xml-data-directory-name'];
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

		$robot_atlas_model->xml_data_file_name = $xml_data_file_name;
		$robot_atlas_model->version = $xml_data_file_version;

		if($robot_atlas_model->update()){
			$response_message = "You have successfully added Robot Atlas";
			$response_data = array("success"=>true, "robot_atlas_id"=>$robot_atlas_model->id, "xml_data_version"=>$xml_data_file_version, "message"=>self::yii_api_echo($response_message), "atlas_id"=>$robot_atlas_model->id);
			self::success($response_data);
		}
	}

	/**
	 * Method to update / Add atlas. For update, provieds an option to delete all previous grids when atlas is being updated with new file.
	 * Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial number of robot, required if new atlas is to be added. Else ignored.</li>
	 *	<li><b>atlas_id</b> : Robot Atlas Id. If 0 is passed, a new atlas is added.</li>
	 *	<li><b>delete_grids</b> : If 1 is passed, all the grids related to this atlas are deleted. Else this parameter is ignored.</li>
	 *	<li><b>xml_data_version</b> :XML data version. pass 0 in case of new atlas.</li>
	 *	<li><b>xml_data</b> :XML data for robot atlas</li>
	 *								
	 *</ul>
	 *Success Responses:
	 *<ul>
	 *
	 *	<li>If serial number provided and atals id passed as 0 (add new):
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"success":true,"robot_atlas_id":"4","xml_data_version":1,"message":"You have successfully added Robot Atlas","atlas_id":"4"}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *
	 *	<li>If xml data is provided and existing atals id passed :
	 *		<ul>
	 *			<li>{"status":0,"result":"{\"success\":true,\"message\":\"You have successfully updated robot atlas data.\"}"}</li>
	 *		</ul>
	 *	</li>
	 *	
	 *	<li>If existing atals id passed and delete_grids is passed as 1 (true)  :
	 *		<ul>
	 *			<li> {"status":0,"result":{"success":true,"robot_atlas_id":"4","xml_data_version":3,"message":"You have successfully deleted 2 grids, You have successfully updated robot atlas data.","atlas_id":"4"}}</li>
	 *		</ul>
	 *	</li>
	 *	
	 *	
	 *</ul>
	 *
	 *Failure Responses: <br />
	 *<ul>
	 *	
	 *	<li>If robot atlas id does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot atlas id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If xml data version is provided, not matching with latest xml
	 *		data version
	 *		<ul>
	 *			<li>{"status":-1,"message":"Version mismatch for xml data."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If xml data version is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter xml_data_version
	 *				in method robot.update_atlas"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If XML data is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter xml_data in
	 *				method robot.update_atlas"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If atlas_id parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter atlas_id in
	 *				method robot.update_atlas"}</li>
	 *		</ul>
	 *	</li>
	 *	
	 *	<li>If serial number does not exists
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a serial number is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robot.add_atlas"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionUpdateAtlas(){
		
		$response_message = "";
		$delete_response_message = "";
		$update_response_message = "";
		
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		
		$robot_atlas_id = Yii::app()->request->getParam('atlas_id', '');
		
		if($robot_atlas_id == 0){
			self::actionPostAtlas();
		}
		
		$delete_grids = Yii::app()->request->getParam('delete_grids', '');
		
		$robot_atlas_model = self::verify_for_robot_atlas_id_existence($robot_atlas_id);

		$robot_xml_data_version = Yii::app()->request->getParam('xml_data_version', '');
		$robot_xml_data_latest_version = $robot_atlas_model->XMLDataLatestVersion;
		
		if($delete_grids == "1"){
			$atlasGridImages= AtlasGridImage::model()->findAll('id_atlas = :id_atlas', array(':id_atlas' => $robot_atlas_id));
			$response_data = array();
			$gridIds = array();
			foreach ($atlasGridImages as $grid_image){
				if($grid_image->delete()){
					$gridIds[] = $grid_image->id;
				}
			}
			
			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot= Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR .  $robot_atlas_model->id_robot;
			$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-atlas-blob-data-directory-name'];
			AppHelper::deleteDirectoryRecursively($uploads_dir);
			
			$delete_response_message = 'You have successfully deleted ' .count($gridIds) .' grids';
		}
		if (isset($robot_xml_data_version)){
			if(isset($robot_xml_data_version) && $robot_xml_data_version != $robot_xml_data_latest_version){
				$response_message = self::yii_api_echo('Version mismatch for xml data.');
				self::terminate(-1, $response_message, APIConstant::DOES_NOT_MATCH_LATEST_XML_DATA_VERSION);
			}
			$old_xml_data_file_path = '';

			if ($robot_xml_data_version){
				$xml_data = Yii::app()->request->getParam('xml_data', '');

				if(!AppCore::validate_atlas_xml_data($xml_data)){
					$response_message = self::yii_api_echo('Invalid xml data.');
					self::terminate(-1, $response_message, APIConstant::INVALID_XML);
				}
				//storing xml data

				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR .  $robot_atlas_model->id_robot;

				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-atlas-xml-data-directory-name'];

				$xml_data_file_name = time(). '.xml';
				$full_file_path_xml_data = $uploads_dir. DIRECTORY_SEPARATOR . $xml_data_file_name;
				$old_xml_data_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $robot_atlas_model->xml_data_file_name;

				$xml_file_handle = fopen($full_file_path_xml_data, 'w');
				fwrite($xml_file_handle,$xml_data);//@todo need to handle file write exceptions
				fclose($xml_file_handle);

				$xml_data_file_version = $robot_xml_data_latest_version + 1;
				$robot_atlas_model->xml_data_file_name = $xml_data_file_name;
				$robot_atlas_model->version = $xml_data_file_version;

			}

			if($robot_atlas_model->update()){
				if($old_xml_data_file_path != ''){
					unlink($old_xml_data_file_path);
				}
				$update_response_message = 'You have successfully updated robot atlas data.';
			}
		}
		
		if($delete_response_message != ''){
			$response_message = $delete_response_message .', '. $update_response_message;
		}else{
			$response_message  = $update_response_message;
		}
		
		$response_data = array("success"=>true, "robot_atlas_id"=>$robot_atlas_model->id, "xml_data_version"=>$robot_atlas_model->version, "message"=>self::yii_api_echo($response_message), "atlas_id"=>$robot_atlas_model->id);
		self::success($response_data);
	}

	/**
	 * Method to give detials of all associated grid Blob data.
	 * Parameters:
	 *ul>
	 *	<li><b>id_atlas</b> :Atlas ID</li>
	 *</ul>
	 *
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				 {"status":0,"result":[{"id_grid":"3","blob_data_file_name":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_atlas_data\/13\/blob\/1356704247.jpg","version":"1"},{"id_grid":"555","blob_data_file_name":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_atlas_data\/14\/blob\/1356705494.jpg","version":"1"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *
	 *Failure Responses: <br />
	 *<ul>
	 *	<li>If id_atlas is incorrect or missing:
	 *		<ul>
	 *			<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *	
	 *	
	 *</ul> 
	 */
	
	public function actionGetAtlasGridMetadata(){
	
		$id_atlas = Yii::app()->request->getParam('id_atlas', '');
		$robotAtlas = self::verify_for_robot_atlas_id_existence($id_atlas);
	
		$atlasGridImages = array();
	
		$atlasGridImages= AtlasGridImage::model()->findAll('id_atlas = :id_atlas', array(':id_atlas' => $robotAtlas->id));
		$response_data = array();
		foreach ($atlasGridImages as $atlasGridImage){
				
			$response_data[] = array(
					'id_grid'=>$atlasGridImage->id_grid,
					'blob_data_file_name'=>$atlasGridImage->BlobDataURL,
					'version'=>$atlasGridImage->version,
			);
				
		}
	
		self::success($response_data);
	}
	
	/**
	 * Method to delete atlas record, XML file and all related grid blob data files.
	 *Parameters:
	 *<ul>
	 *		<li><b>atlas_id</b> :Robot Atlas Id</li>
	 *	</ul>
	 *	Success Response:
	 *	<ul>
	 *		<li>If everything goes fine
	 *			<ul>
	 *				<li>
	 *					{"status":0,"result":"{\"success\":true,\"message\":\"You have successfully deleted robot atlas.\"}"}
	 *				</li>
	 *			</ul>
	 *		</li>
	 *	</ul>
	 *
	 *	Failure Responses: <br />
	 *	<ul>
	 *		<li>If robot atlas id does not exist
	 *			<ul>
	 *				<li>{"status":-1,"message":"Robot atlas id does not exist"}</li>
	 *			</ul>
	 *		</li>
	 *		<li>If a parameter is missing
	 *			<ul>
	 *				<li>{"status":-1,"message":"Missing parameter robot_atlas_id in
	 *					method robot.get_atlas_data"}</li>
	 *			</ul>
	 *		</li>
	 *	</ul>
	 */	
	public function actionDelete(){
		$id_atlas = Yii::app()->request->getParam('atlas_id', '');
		$robot_atlas = self::verify_for_atlas_id_existence($id_atlas);
		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot_atlas = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $robot_atlas->id_robot;
		if($robot_atlas->delete()){
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot_atlas);
		
			$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully deleted robot atlas.'));
			
			self::success($response_data);
		}
	}
	
	/**
	 * Method to get atlas details.
	*Parameters:
	*<ul>
	*	<li><b>serial_number</b> :Serial number of robot</li>
	*</ul>
	*Success Response:
	*<ul>
	*	<li>If everything goes fine
	*		<ul>
	*			<li>
	*				{"status":0,"result":{"atlas_id":"32","xml_data_url":"http:\/\/localhost\/Neato_Server\/Server_Yii\/Neato\/robot_atlas_data\/32\/xml\/1357653845.xml","version":"1"}}
	*			</li>
	*		</ul>
	*	</li>
	*</ul>
	*
	*Failure Responses: <br />
	*<ul>
	*	<li>If robot serial_number does not exist
	*		<ul>
	*			<li>{"status":-1,"message":"Robot serial_number does not exist."}</li>
	*		</ul>
	*	</li>
	*	<li>If robot atlas id does not exist
	*		<ul>
	*			<li>{"status":-1,"message":"Robot atlas does not exist for this robot"}</li>
	*		</ul>
	*	</li>
	*	<li>If a parameter is missing
	*		<ul>
	*			<li>{"status":-1,"message":"Missing parameter serial_number in
	*				method robot.get_atlas_data"}</li>
	*		</ul>
	*	</li>
	*</ul>
	 */
	public function actionGetData(){
		$robot = self::verify_for_robot_serial_number_existence(Yii::app()->request->getParam('serial_number', ''));
		$robot_atlas_model = RobotAtlas::model()->findByAttributes(array('id_robot'=> $robot->id));
		if($robot_atlas_model){
			$response_data = array( "atlas_id" => $robot_atlas_model->id,
					"xml_data_url" => $robot_atlas_model->XMLDataURL,
					"version" => $robot_atlas_model->version,
			);
			self::success($response_data);
		}else{
			$response_message = self::yii_api_echo("Robot atlas does not exist for this robot");
			self::terminate(-1, $response_message, APIConstant::ROBOT_ATLAS_ID_DOES_NOT_EXIST);
		}
		
	}

	/**
	 * A Deligate method which is intended to be called from Web-End.
	 * Captures file content from _FILE and pass on standard method.
	 * @param xml_data_file_name file name for XML.
	 * 		@throws error if xml file not found.
	 * 
	 * delegates to actionPostAtlas();  
	 */
	public function actionAdd(){
		
		if( !isset($_FILES['RobotAtlas']) || (! file_exists($xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name']) &&
				! file_exists($xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name']))){
			$response_message = self::yii_api_echo('Please Provide XML data.');
			self::terminate(-1, $response_message, APIConstant::MISSING_XML_DATA);
		}
		
		$encoded_xml_data="";
		if(file_exists($xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name'])){
			$xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name'];
			$handle = fopen($xml_data_temp_file_path, "r");
			$original_content = fread($handle, filesize($xml_data_temp_file_path));
			fclose($handle);
			$encoded_xml_data = $original_content;
		}else{
			// 			unset($_POST['blob_data_version']);
		}
		
		$_POST['xml_data'] = $encoded_xml_data;
		
		self::actionPostAtlas();
		
		
	}

	
	/**
	 * A Deligate method which is intended to be called from Web-End.
	 * Captures file content from _FILE and pass on standard method.
	 * @param xml_data_file_name file name for XML.
	 * 		@throws error if xml file not found.
	 * 
	 * delegates to actionUpdateAtlas();  
	 */
	public function actionUpdate(){
	
		if( !isset($_FILES['RobotAtlas']) || (! file_exists($xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name']) &&
				! file_exists($xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name']))){
			$response_message = self::yii_api_echo('Please Provide XML data.');
			self::terminate(-1, $response_message, APIConstant::MISSING_XML_DATA);
		}
	
		$encoded_blob_data="";
		if(file_exists($xml_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name'])){
			$blob_data_temp_file_path = $_FILES['RobotAtlas']['tmp_name']['xml_data_file_name'];
			$handle = fopen($blob_data_temp_file_path, "r");
			$original_content = fread($handle, filesize($blob_data_temp_file_path));
			fclose($handle);
			$encoded_blob_data = $original_content;
		}else{
			// 			unset($_POST['blob_data_version']);
		}
	
		$_POST['xml_data'] = $encoded_blob_data;
	
		self::actionUpdateAtlas();
	
	
	}
	
}