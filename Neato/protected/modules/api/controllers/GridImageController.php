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
	
	public function actionPostGridImage(){
		
		$id_atlas = Yii::app()->request->getParam('id_atlas', '');
		$robotAtlas = self::verify_for_robot_atlas_id_existence($id_atlas);
		
		$id_grid = Yii::app()->request->getParam('id_grid', '');
		$id_grid = self::verify_for_empty_grid_id($id_grid);
		self::verify_for_atlas_id_grid_id_repetition($id_atlas,$id_grid);
		
		$grid_image = new AtlasGridImage();
		$grid_image->id_atlas =$robotAtlas->id;
		$grid_image->id_grid = $id_grid;
		
		if(!$grid_image->save()){
			var_dump($grid_image->id_atlas);
			var_dump($grid_image->id_grid);
			var_dump($grid_image->version);
			var_dump($grid_image->blob_data_file_name);
			
			echo '$grid_image->save() failed';die;
		}
		
		$grid_image->version = 1;
		
				
		//storing blob data
		$encoded_blob_data = Yii::app()->request->getParam('encoded_blob_data', '');
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
			$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $robotAtlas->id_robot;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot)){
				mkdir($uploads_dir_for_robot);
			}
			$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-atlas-blob-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}
			$full_file_path_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $blob_data_file_name;
		
			$blob_file_handle = fopen($full_file_path_blob_data, 'w');
			fwrite($blob_file_handle, $blob_data); //@todo need to handle file write exceptions
			fclose($blob_file_handle);
		
			$grid_image->blob_data_file_name= $blob_data_file_name;
		}
		
		if($grid_image->update()){
			$response_message = self::yii_api_echo('Atlas Grid image stored successfully.');
			$response_data = array("success"=>true, "id_grid_image"=> $grid_image->id, "id_atlas"=>$grid_image->id_atlas, "id_grid"=>$grid_image->id_grid, "version"=> $grid_image->version, "blob_data_file_name"=>$grid_image->blob_data_file_name);
			self::success($response_data);
		}else{
			
// 			var_dump($grid_image->id_atlas);
// 			var_dump($grid_image->id_grid);
// 			var_dump($grid_image->version);
// 			var_dump($grid_image->blob_data_file_name);
			
// 			echo 'failed postGridImage()';
		}
	}

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
	public function actionUpdateGridImage(){
	
		$id_atlas = Yii::app()->request->getParam('id_atlas', '');
		$robotAtlas = self::verify_for_robot_atlas_id_existence($id_atlas);
	
		$id_grid = Yii::app()->request->getParam('id_grid', '');
		$grid_image = self::verify_for_atlas_id_grid_id_existence($id_atlas,$id_grid, true);
		
		if($grid_image === null) { self::actionPostGridImage();}
		
		$robot_blob_data_version = Yii::app()->request->getParam('blob_data_version', '');
		$robot_blob_data_latest_version = $grid_image->BlobDataLatestVersion;
		
		if (isset($robot_blob_data_version)){
			if(isset($robot_blob_data_version) && $robot_blob_data_version != $robot_blob_data_latest_version){
				$response_message = self::yii_api_echo('Version mismatch for blob data.');
				self::terminate(-1, $response_message, APIConstant::DOES_NOT_MATCH_LATEST_BLOB_DATA_VERSION);
			}
		}
		
		//storing blob data
		$encoded_blob_data = Yii::app()->request->getParam('encoded_blob_data', '');
		$blob_data_file_name = '';
		$blob_data_file_version = $grid_image->BlobDataLatestVersion;
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
			$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $robotAtlas->id_robot;
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir_for_robot)){
				mkdir($uploads_dir_for_robot);
			}
			$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . Yii::app()->params['robot-atlas-blob-data-directory-name'];
			// Add check to see if the folder already exists
			if(!is_dir($uploads_dir)){
				mkdir($uploads_dir);
			}
			$full_file_path_blob_data = $uploads_dir. DIRECTORY_SEPARATOR . $blob_data_file_name;
	
			$blob_file_handle = fopen($full_file_path_blob_data, 'w');
			fwrite($blob_file_handle, $blob_data); //@todo need to handle file write exceptions
			fclose($blob_file_handle);
	
			$grid_image->blob_data_file_name= $blob_data_file_name;
			$grid_image->version = $blob_data_file_version + 1;
		}
			
		if($grid_image->update()){
			$response_message = self::yii_api_echo('Atlas Grid image stored successfully.');
			$response_data = array("success"=>true, "id_grid_image"=> $grid_image->id, "id_atlas"=>$grid_image->id_atlas, "id_grid"=>$grid_image->id_grid, "version"=> $grid_image->version, "blob_data_file_name"=>$grid_image->blob_data_file_name);
			self::success($response_data );
		}else{
				
			var_dump($grid_image->id_atlas);
			var_dump($grid_image->id_grid);
			var_dump($grid_image->version);
			var_dump($grid_image->blob_data_file_name);
				
			echo 'failed updateGridImage()';
		}
	}
	
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
	
	public function actionDeleteGridImage(){
		$id_atlas = Yii::app()->request->getParam('id_atlas', '');
		$robotAtlas = self::verify_for_robot_atlas_id_existence($id_atlas);
		
		$id_grid = Yii::app()->request->getParam('id_grid', '');
		$grid_image = self::verify_for_atlas_id_grid_id_existence($id_atlas,$id_grid, false);
		
		$_POST['id_grid_image'] = AppHelper::two_way_string_encrypt($grid_image->id);
		
		self::actionDelete();
	}	
	
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
