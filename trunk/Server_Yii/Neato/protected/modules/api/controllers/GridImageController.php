<?php

/**
 * The API RobotMapController is meant for all robot-map related API actions.
 */
class GridImageController extends APIController {
	
	/**
	 * This method adds a blob data for grid and associate it with atlas.
	 * Parameters:
	 *	<ul>
	 *		<li><b>id_atlas</b> :Atlas ID</li>
	 *		<li><b>id_grid</b> :Grid ID</li>
	 *		<li><b>encoded_blob_data</b> :Blob data / Iamge :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
	 *		</li>
	 *	</ul>
	 *	
	 *	
	 *	Success Response:
	 *	<ul>
	 *		<li>If everything goes fine
	 *			<ul>
	 *				<li>
	 *					{"status":0,"result":"{\"success\":true,\"id_grid_image\":\"22\",\"id_atlas\":\"28\",\"id_grid\":\"1\",\"version\":1,\"blob_data_file_name\":\"1357583122.jpg\"}"}
	 *				</li>
	 *			</ul>
	 *		</li>
	 *	</ul>
	 *	
	 *	Failure Responses: <br />
	 *	<ul>
	 *		<li>If id_atlas is incorrect:
	 *			<ul>
	 *				<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
	 *			</ul>
	 *			
	 *		<li>If grid image exist for provided 'id_atlas' and 'id_grid' combination:
	 *			<ul>
	 *				<li> {"status":-1,"message":"Combination of atlas id and grid id exist. Try updating for same."}</li>
	 *			</ul>	
	 *		</li>
	 *									
	 *	</ul>
	 */
	


	/**
	 * This Method Updates/Add the existing blob data for provided id_grid.
	 *Parameters:
	 *	<ul>
	 *		<li><b>id_atlas</b> :Atlas ID</li>
	 *		<li><b>id_grid</b> :Grid ID. If id does not exist, it will add new grid image</li>
	 *		<li><b>blob_data_version</b> :BLOB data version. Pass 0 if adding new grid image.</li>
	 *		<li><b>encoded_blob_data</b> :Blob data / Iamge :Base 64 encoded string (Optional) . You can generate base 64 encoded string for a file using this <a href='robot_data_encode.php' target='_blank'>link</a>
	 *		</li>
	 *	</ul>
	 *	
	 *	Success Response:
	 *	<ul>
	 *		<li>If everything goes fine
	 *			<ul>
	 *				<li>
	 *					 {"status":0,"result":"{\"success\":true,\"id_grid_image\":\"23\",\"id_atlas\":\"28\",\"id_grid\":\"0\",\"version\":1,\"blob_data_file_name\":\"1357583291.jpg\"}"}
	 *				</li>
	 *			</ul>
	 *		</li>						
	 *		
	 *	</ul>
	 *
	 *	Failure Responses: <br />
	 *	<ul>
	 *		
	 *		<li>If id_atlas is incorrect:
	 *			<ul>
	 *				<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
	 *			</ul>
	 *		
	 *	</ul>
	 */
	
	/**
	 *This method is a delegate and intended to be called from Web-End.
	 *Purpose is to capture blob-files from _FILES and pass it's content in encoded format to standard method.
	 *It also captures id_grid from _POST and pass the same.   
	 *	
	 *		@throws error if id_grid is not sent.
	 *		@throws error if blob_data_file_name not found.
	 *
	 *		delegates to actionPostGridImage().
	 */
	public function actionAdd(){		
		if(!$_POST['AtlasGridImage']['id_grid']){
			$response_message = self::yii_api_echo('Please Provide Grid ID.');
			self::terminate(-1, $response_message, APIConstant::GRID_ID_MISSING);
		}else{
			self::verify_for_empty_grid_id($_POST['AtlasGridImage']['id_grid']);
		}
		
		if( !isset($_FILES['AtlasGridImage']) || (! file_exists($xml_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name']) &&
				! file_exists($xml_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name']))){
			$response_message = self::yii_api_echo('Please Provide Blob data.');
			self::terminate(-1, $response_message, APIConstant::BLOB_DATA_MISSING);
		}
		
		$encoded_blob_data="";
		if(file_exists($xml_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name'])){
			$blob_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name'];
			$handle = fopen($blob_data_temp_file_path, "r");
			$original_content = fread($handle, filesize($blob_data_temp_file_path));
			fclose($handle);
			$encoded_blob_data = base64_encode($original_content);
		}else{
// 			unset($_POST['blob_data_version']);
		}
		
		$_POST['id_grid'] = $_POST['AtlasGridImage']['id_grid'];
		$_POST['encoded_blob_data'] = $encoded_blob_data;
		
		self::actionPostGridImage();
	}

	/**
	 *This method is a delegate and intended to be called from Web-End.
	 *Purpose is to capture blob-files from _FILES and pass it's content in encoded format to standard method.
	 *It also captures id_grid from _POST and pass the same.
	 *
	 *		@throws error if id_grid is not sent.
	 *		@throws error if blob_data_file_name not found.
	 *
	 *		delegates to actionUpdateGridImage().
	 */
	
	public function actionUpdate(){

		
		if( !isset($_FILES['AtlasGridImage']) || (! file_exists($xml_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name']) &&
				! file_exists($xml_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name']))){
			$response_message = self::yii_api_echo('Please Provide Blob data.');
			self::terminate(-1, $response_message, APIConstant::BLOB_DATA_MISSING);
		}
			
		
		$encoded_blob_data="";
		if(file_exists($xml_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name'])){
			$blob_data_temp_file_path = $_FILES['AtlasGridImage']['tmp_name']['blob_data_file_name'];
			$handle = fopen($blob_data_temp_file_path, "r");
			$original_content = fread($handle, filesize($blob_data_temp_file_path));
			fclose($handle);
			$encoded_blob_data = base64_encode($original_content);
		}else{
// 			unset($_POST['blob_data_version']);
		}
		
		$_POST['id_grid'] = Yii::app()->request->getParam('grid_id', ''); 
		$_POST['encoded_blob_data'] = $encoded_blob_data;
		
		self::actionUpdateGridImage();
	}

	/**
	 *This method is a delegate and intended to be called from API.
	 *Purpose is to capture id_atlas and id_grid, if combination is not repeated, pass on the DB id to standard method.
	 *
	 * Parameters:
	 *	<ul>
	 *		<li><b>id_atlas</b> :Atlas ID</li>
	 *		<li><b>id_grid</b> :Grid ID</li>
	 *		</li>
	 *	</ul>
	 *	
	 *	Success Response:
	 *	<ul>
	 *		<li>If everything goes fine
	 *			<ul>
	 *				<li>
	 *					 {"status":0,"result":{"success":true,"message":"You have successfully deleted grid image."}}
	 *				</li>
	 *			</ul>
	 *		</li>
	 *	</ul>
	 *
	 *	Failure Responses: <br />
	 *	<ul>
	 *				
	 *		<li>If id_atlas is incorrect:
	 *			<ul>
	 *				<li> {"status":-1,"message":"Robot atlas id does not exist"}</li>
	 *			</ul>
	 *			
	 *		<li>If grid image unavailable for provided 'id_atlas' and 'id_grid' combination:
	 *			<ul>
	 *				<li> {"status":-1,"message":"Combination of atlas id and grid id does not exist"}</li>
	 *			</ul>	
	 *		</li>
	 *		<li>If 'id_grid' is incorrect:
	 *			<ul>
	 *				<li> {"status":-1,"message":"Combination of atlas id and grid id does not exist"}</li>
	 *			</ul>	
	 *		</li>
	 *		
	 *	</ul>
	 *	
	 *	delegates to actionDelete().
	 */
	

	
	/**
	 * This method delets record of grid blob data from database and removes corresponding file from server directory.
	 * @param id_grid_image database ID for specific grid blob data record.
	 * 		@throws error if given ID not found.
	 */	
	
	public function actionDelete(){
		
		$id_grid_image = Yii::app()->request->getParam('id_grid_image', '');
		$id_grid_image = AppHelper::two_way_string_decrypt($id_grid_image);
		$grid_image = self::verify_for_grid_image_id_existence($id_grid_image);
		
		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_grid_image= Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $grid_image->idAtlas->id_robot. DIRECTORY_SEPARATOR .Yii::app()->params['robot-atlas-blob-data-directory-name'];
		$path_grid_image = $uploads_dir_for_grid_image . DIRECTORY_SEPARATOR . $grid_image->blob_data_file_name;
		
		if($grid_image->delete()){
			AppHelper::deleteFile($path_grid_image);
			$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully deleted grid image.'));
			self::success($response_data);
		}else{
				$response_message = self::yii_api_echo('Error while deleting grid image');
				self::terminate(-1, $response_message, APIConstant::ERROR_DELETING_GRID_IMAGE);
		
		}
	}
}
