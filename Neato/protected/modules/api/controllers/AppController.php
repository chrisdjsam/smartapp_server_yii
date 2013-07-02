<?php

/**
 * The API AppController is meant for all version related API actions.
 */
class AppController extends APIController {


	/**
	 *
	 * Web service to check app versions for upgrades.
	 *
	 *Parameters:
	* <ul>
	* 	<li><b>api_key</b> :Your API Key</li>
	* 	<li><b>app_id</b> :Application ID</li>
	* 	<li><b>current_appversion</b> :Application version on device</li>
	* 	<li><b>os_type</b> :Operating system on device</li>
	* 	<li><b>os_version</b> :Operating system version on device</li>
	* 	   
	* </ul>
	* Success Responses:
	* <ul>
	* 	<li>If everything goes fine
	* 		<ul>
	* 			<li>
	* 				{"status":0,"result":{"current_app_version":"1.0.0.1","latest_version":"0.5.1.00","latest_version_url":"http:\/\/rajatogo.com\/public_shared\/GTArena_0.5.1.00.apk","upgrade_status":"0"}}
	* 			</li>
	* 		</ul>
	* 	</li>
	* 	
	* </ul>
	* 
	* Failure Responses: <br />
	* <ul>
	* 
	* 	<li>If API Key is missing or not correct:
	* 		<ul>
	* 			<li>{"status":-1,"message":"Method call failed the API
	* 				Authentication"}</li>
	* 		</ul>
	* 	</li>
	* 	<li>If Application details not found for given ID.
	* 		<ul>
	* 			<li>{"status":-1,"message":"App Id does not exist."}</li>
	* 		</ul>
	* 	</li>
	* 
	* </ul>
	 */
	public function actionCheckForUpgrades() {
		
		$app_id = Yii::app()->request->getParam('app_id', '');
		$app_info = AppInfo::model()->findByAttributes(array('app_id'=> $app_id));
		
		$response_message = "";
		if($app_info !== null){
			$response_data = array("current_app_version"=>$app_info->current_app_version,"latest_version"=> $app_info->latest_version,  "latest_version_url"=> $app_info->latest_version_url, "upgrade_status"=> $app_info->upgrade_status);

			self::success($response_data);
		}else{
			$response_message = "App Id does not exist.";
                        self::terminate(-1, $response_message, APIConstant::APP_DETAILS_NOT_FOUND);
                        
		}
	
	}

	
	
	/**
	 * Method to add application version. called from web end.
	 * decripts parameter values and delegates to API method.
	 */
	public function actionAddApp(){
		
		$app_id = $_POST['AppInfo']['app_id'];
		$app_id = trim($app_id);
		if(!is_numeric($app_id) && !is_int($app_id) ){
			$response_message = "app_id should be an integer only.";
			self::terminate(-1, $response_message, APIConstant::APP_ID_SHOULD_BE_INTEGER);
		}	
		
		$appInfo = AppInfo::model()->findByAttributes(array("app_id"=>$app_id));
		
		if($appInfo !== null){
			$response_message = "app_id already exist.";
			self::terminate(-1, $response_message, APIConstant::APP_ID_ALREADY_EXIST);
		}
		
		$upgrade_status = $_POST['AppInfo']['upgrade_status'];
		if($upgrade_status == null){
			$response_message = "Please mention upgrade status.";
			self::terminate(-1, $response_message, APIConstant::UPGRADE_STATUS_MISSING);
		}
		
		$current_app_version = $_POST['AppInfo']['current_app_version'];
		$os_type = $_POST['AppInfo']['os_type'];
		$os_version = $_POST['AppInfo']['os_version'];
		$latest_version = $_POST['AppInfo']['latest_version'];
		$latest_version_url = $_POST['AppInfo']['latest_version_url'];
		
		$appInfo = new AppInfo();
		$appInfo->app_id = $app_id;
		$appInfo->current_app_version = $current_app_version;
		$appInfo->os_type = $os_type;
		$appInfo->os_version = $os_version;
		$appInfo->latest_version = $latest_version;
		$appInfo->latest_version_url = $latest_version_url;
		$appInfo->upgrade_status = $upgrade_status;

		$response_message = "";
	
		if($appInfo->save()){
			$response_message = "You have successfully added new app version.";
			self::success($response_message);
		}else{
			$response_message = "Problem adding new app version.";
			self::terminate(-1, $response_message, APIConstant::ERROR_IN_ADDING_NEW_APP);
		}

		$this->renderPartial('/default/defaultView', array('content' => $content));
		
	}
	
	/**
	 * Method to add application version.  
	 */
	public function actionAdd(){
		
	}

	
	/**
	 * Method to update application version. called from web end.
	 * decripts parameter values and delegates to API method.
	 */
	public function actionAppUpdate(){
		
		$app_id = $_POST['app_id'];
		$appInfo = AppInfo::model()->findByAttributes(array("app_id"=>$app_id));
		
		if($appInfo == null){
			$response_message = "app_id does not exist.";
			self::terminate(-1, $response_message, APIConstant::APP_DETAILS_NOT_FOUND);
		}	
		
		
		$current_app_version = $_POST['AppInfo']['current_app_version'];
		$os_type = $_POST['AppInfo']['os_type'];
		$os_version = $_POST['AppInfo']['os_version'];
		$latest_version = $_POST['AppInfo']['latest_version'];
		$latest_version_url = $_POST['AppInfo']['latest_version_url'];
		$upgrade_status = $_POST['AppInfo']['upgrade_status'];

		$appInfo->current_app_version = $current_app_version;
		$appInfo->os_type = $os_type;
		$appInfo->os_version = $os_version;
		$appInfo->latest_version = $latest_version;
		$appInfo->latest_version_url = $latest_version_url;
		$appInfo->upgrade_status = $upgrade_status;
	
		$response_message = "";
	
		if($appInfo->update()){
			$response_message = "You have successfully updated app version $app_id.";
			self::success($response_message);
		}else{
			$response_message = "Problem updating app version.";
			self::terminate(-1, $response_message, APIConstant::ERROR_UPDATING_APP_VERSION);
		}
	
		$this->renderPartial('/default/defaultView', array('content' => $content));
	
	}
	
	/**
	 * Method to update application version. called from web end.
	 * decripts parameter values and delegates to API method.
	 */
	public function actionAppDelete(){

		$app_id= Yii::app()->request->getParam('h', '');
		$app_id = AppHelper::two_way_string_decrypt($app_id);
		$appInfo = AppInfo::model()->findByAttributes(array("app_id"=>$app_id));
	
		if($appInfo == null){
			$response_message = "app_id does not exist.";
			self::terminate(-1, $response_message, APIConstant::APP_DETAILS_NOT_FOUND);
		}
		
	
		$response_message = "";
	
		if($appInfo->delete()){
			$response_message = "You have successfully deleted app version $app_id.";
			self::success($response_message);
		}else{
			$response_message = "Problem deleting app version.";
			self::terminate(-1, $response_message, APIConstant::ERROR_DELETING_APP_VERSION);
		}
	
		$this->renderPartial('/default/defaultView', array('content' => $content));
	
	}
	

}