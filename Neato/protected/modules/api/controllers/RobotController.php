<?php

/**
 * The API RobotController is meant for all robot related API actions.
 */
class RobotController extends APIController {

	/**
	 * API to create new robot
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>name</b> :Name of the robot</li>
	 *	<li><b>serial_number</b> :Serial Number of the robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot
	 *				created successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Robot serial number is duplicate
	 *		<ul>
	 *			<li>{"status":-1,"message":"This robot serial number already
	 *				exists."}</li>
	 *		</ul>
	 *	</li>
	 *
	 *	<li>If Jabber service is not able to create chat user
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot could not be created because
	 *				jabber service in not responding."}</li>
	 *		</ul>
	 *	</li>
	 *
	 *</ul>
	 */
	public function actionCreate(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot_name = Yii::app()->request->getParam('name', '');

		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if($robot !== null ){
			if ($robot->serial_number === $robot_serial_no){
				$response_message = self::yii_api_echo('This robot serial number already exists.');
				self::terminate(-1, $response_message);
			}
		}
		$model = new Robot();
		$model->name = $robot_name;
		$model->serial_number = $robot_serial_no;

		$chat_details = AppCore::create_chat_user_for_robot();
		if(!$chat_details['jabber_status']){
			$message = self::yii_api_echo("Robot could not be created because jabber service is not responding.");
			self::terminate(-1, $message);
		}
		$model->chat_id = $chat_details['chat_id'];
		$model->chat_pwd = $chat_details['chat_pwd'];

		if($model->save()){
			$response_data = array("success"=>true, "message"=>self::yii_api_echo('Robot created successfully.'));
			self::success($response_data);
		}
		else{
			$response_message = self::yii_api_echo('Robot could not be created because jabber service is not responding');
			self::terminate(-1, $response_message);
		}
	}
   /**
    * Method to check if robot is online for given robot serial number.
    * 
    * Parameters:
	*ul>
	 *		<li><b>serial_number</b> :Serial Number of the robot</li>
	 *	</ul>
	 *	Success Response:
	 *	<ul>
	 *		<li>If everything goes fine
	 *			<ul>
	 *				<li>{"status":0,"result":{"success":true,"message":"Robot
	 *					is online / offline."}}</li>
	 *			</ul>
	 *		</li>
	 *	</ul>
	 *
	 *	Failure Responses: <br />
	 *	<ul>
	 *
	 *		<li>If a serial_number is missing
	 *			<ul>
	 *				<li>{"status":-1,"message":"Missing parameter serial_number in method
	 *					robot.is_robot_online"}</li>
	 *			</ul>
	 *		</li>
	 *		<li>If serial number does not exist
	 *			<ul>
	 *				<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *			</ul>
	 *		</li>
	 *
	 *	</ul>
    */
	public function actionIsOnline(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		
		if($robot !== null ){
			$online_users_chat_ids = AppCore::getOnlineUsers();
			if(in_array($robot->chat_id, $online_users_chat_ids)){
				$response_message = "Robot ".$robot_serial_no." is online.";
				$response_data = array("online"=>true, "message"=>$response_message);
			}else {
				$response_message = "Robot ".$robot_serial_no." is offline.";
				$response_data = array("online"=>false, "message"=>$response_message);
			}
			self::success($response_data);
		}
		
	}
	
        
	public function actionGetRobotPresenceStatus(){
            
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		
		if($robot !== null ){
			$online_users_chat_ids = AppCore::getOnlineUsers();
			if(in_array($robot->chat_id, $online_users_chat_ids)){
				$response_message = "Robot ".$robot_serial_no." is online.";
				$response_data = array("online"=>true, "message"=>$response_message);
			}else {
				$response_message = "Robot ".$robot_serial_no." is offline.";
				$response_data = array("online"=>false, "message"=>$response_message);
			}
			self::success($response_data);
		}
                
		
	}        
        
	public function actionIsRobotOnlineVirtual(){
            
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		
		if($robot !== null ){
			
                        $data = AppCore::getLatestPingTimestampFromRobot($robot->id);
                        
                        $online_users_chat_ids = AppCore::getOnlineUsers();
                        if(in_array($robot->chat_id, $online_users_chat_ids)){
                                $response_message = "Robot ".$robot_serial_no." is online.";
                                $response_data = array("online"=>true, "message"=>$response_message);
                        }else if(!empty ($data)){
                                $latest_ping_timestamp = strtotime($data[0]->ping_timestamp);
                                
                                $app_config = AppConfiguration::model()->find('_key = :_key', array(':_key' => 'ROBOT_PING_INTERVAL'));
                                $robot_ping_interval = $app_config->value;
                                
                                $current_system_timestamp = time();
                                $time_diff = ($current_system_timestamp - $latest_ping_timestamp);
                                
                                if($time_diff > $robot_ping_interval){
                                    $response_message = "Robot ".$robot_serial_no." is offline.";
                                    $response_data = array("online"=>false, "message"=>$response_message);
                                } else {
                                    $response_message = "Robot ".$robot_serial_no." is online.";
                                    $response_data = array("online"=>true, "message"=>$response_message);
                                }
                                
                        } else {
                                $response_message = "Robot ".$robot_serial_no." is offline.";
                                $response_data = array("online"=>false, "message"=>$response_message);
                        }
                        self::success($response_data);

		}
                
		
	}        
        
        
	/**
	 *  API to get robots information
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"id":"65","name":"desk
	 *				cleaner59","serial_number":"robo1","chat_id":"1350924155_robot@rajatogo","chat_pwd":"1350924155_robot"}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and user association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"id":"68","name":"room
	 *				cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo","chat_pwd":"1350987452_robot","users":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and user association does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"id":"70","name":"room
	 *				cleaner","serial_number":"robo1","chat_id":"1351501366_robot@rajatogo","chat_pwd":"1351501366_robot","users":[]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetDetails(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$users_arr = array();
		foreach ($robot->usersRobots as $user_robots){
			$user_details = array();
			$user_details['id'] = $user_robots->idUser->id;
			$user_details['name'] = $user_robots->idUser->name;
			$user_details['email'] = $user_robots->idUser->email;
			$user_details['chat_id'] = $user_robots->idUser->chat_id;

			$users_arr[] = $user_details;
		}
		$response_data = array("id"=>$robot->id,"name"=>$robot->name,"serial_number"=>$robot->serial_number,"chat_id"=>$robot->chat_id,"chat_pwd"=>$robot->chat_pwd, "users"=>$users_arr);
		self::success($response_data);
	}
	
	
/**
	 * Deletes a set of robots that were selected by the user from the front end.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeleteRobot()
	{
		self::check_for_admin_privileges();
		if (isset($_REQUEST['chooseoption'])){
			foreach ($_REQUEST['chooseoption'] as $robo_id){
				$robot = Robot::model()->findByAttributes(array('id' => $robo_id));

				$_POST['serial_number'] = $robot->serial_number;
				self::actionDelete(true); 
				
			}

			$count = count($_REQUEST['chooseoption']);
			$message = AppCore::yii_echo("You have deleted %s robot successfully", $count);
			if ($count > 1){
				$message = AppCore::yii_echo("You have deleted %s robots successfully",$count);
			}
			Yii::app()->user->setFlash('success', $message);
		}else{
			Yii::app()->user->setFlash('error', AppCore::yii_echo("No robot selected to delete"));
		}
		$this->redirect(Yii::app()->request->baseUrl.'/robot/list');
	}
	
	
	/**
	 * Metod to delete a robot for given serial number.
	 * 
	 * Parameters:
	 * <ul>
	 * 	<li><b>serial_number</b> :Serial number of robot</li> 
	 * 	<ul>
	 * Success Responses:
	* <ul>
	* 		<li>If everything goes fine
	* 			<ul>
	* 				<li>{"status":0,"result":{"success":true,"message":"You have deleted robot 123 successfully"}}
	* 				</li>
	* 			</ul>
	* 		</li>
	* 								
	* 	</ul>
	* 
	* 	Failure Responses: <br />
	* 	<ul>
	* 		<li>If parameter serial_number is missing
	* 			<ul>
	* 				<li>{"status":-1,"message":"Missing parameter serial_number in
	* 					method robot.get_details"}</li>
	* 			</ul>
	* 		
	* 		<li>If serial number does not exist
	* 			<ul>
	* 				<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
	* 			</ul>
	* 		</li>
	* 	</ul>
	 * 
	 * 
	 */
	public function actionDelete($prevent_termination = false){
		
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
                
		if($robot !== null ){
			$robot_map_id_arr = array();
			$robot_schedule_id_arr = array();
			foreach ($robot->robotMaps as $robot_map){
				$robot_map_id_arr[] = $robot_map->id;
			}
			foreach ($robot->robotSchedules as $robot_schedule){
				$robot_schedule_id_arr[] = $robot_schedule->id;
			}

			$chat_id = $robot->chat_id;
			if($robot->delete()){
				AppCore::delete_chat_user($chat_id);
				AppCore::delete_robot_map_data($robot_map_id_arr);
				AppCore::delete_robot_schedule_data($robot_schedule_id_arr);
				AppCore::delete_robot_atlas_data($robot->id);
				
				$response_message = "You have deleted robot $robot_serial_no successfully";
				$response_data = array("success"=>true, "message"=>$response_message);
				
				if(!$prevent_termination){
					self::success($response_data);
				}
				
			}else if(!$prevent_termination){
				$message = self::yii_api_echo("error deleting robot $robot_serial_no.");
				self::terminate(-1, $message);
			}
		}
			
		
	}
	
	/**
	 * Method to set robot profile parameters and values.
	 * 
	 * Parameters:
	* <ul>
	* 		<li><b>api_key</b> :Your API Key</li>
	* 		<li><b>serial_number</b> :Serial Number of robot</li>
	* 		<li><b>profile</b> :Map of key=>value pairs, e.g.
	* 			profile{'name'=>'room cleaner'}</li>
	* 	</ul>
	* 	Success Response:
	* 	<ul>
	* 		<li>{"status":0,"result":"1"}</li>
	* 	</ul>
	* 
	* 	Failure Responses: <br />
	* 	<ul>
	* 
	* 		<li>If API Key is missing or not correct:
	* 			<ul>
	* 				<li>{"status":-1,"message":"Method call failed the API
	* 					Authentication"}</li>
	* 			</ul>
	* 		</li>
	* 
	* 		<li>If serial_number is not provided:
	* 			<ul>
	* 				<li>{"status":-1,"message":"Missing parameter serial_number in
	* 					method robot.set_profile_details"}</li>
	* 			</ul>
	* 		</li>
	* 
	* 		<li>If profile key is not added:
	* 			<ul>
	* 				<li>{"status":-1,"message":"Missing parameter profile in method
	* 					robot.set_profile_details"}</li>
	* 			</ul>
	* 		</li>
	* 
	* 		<li>If key is added but value is not provided :
	* 			<ul>
	* 				<li>{"status":-1,"message":"Invalid value for key name."}</li>
	* 			</ul>
	* 		</li>
	* 	</ul>
	* 
	* 
    */
	
	public function actionSetProfileDetails(){
	
		$robot = self::verify_for_robot_serial_number_existence(Yii::app()->request->getParam('serial_number', ''));
	
		$robot_profile = Yii::app()->request->getParam('profile', '');

		if ($robot !== null){
			foreach ($robot_profile as $key => $value){
                                $key = trim($key);
				switch ($key) {
					case "name":
						$robot->name = $value;
						$robot->save();
						break;
					
					default:
                                            $data = RobotKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $key, ':robot_id' => $robot->id));
                                            if(!empty($data)){
//                                                if(empty($value)){
//                                                    RobotKeyValues::model()->deleteAll('id = :id', array(':id' => $data->id));
//                                                } else {
                                                    $data->value = $value;
                                                    $data->update();
//                                                }
                                            } else {
//                                                if(empty($value)){
//                                                    continue;
//                                                }                                                
                                                $robot_key_value = new RobotKeyValues();
                                                $robot_key_value->robot_id = $robot->id;
                                                $robot_key_value->_key = $key ;
                                                $robot_key_value->value = $value ;
                                                $robot_key_value->save();                                                    
                                            }
                                        break;
				}
			}
			self::success(1);
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}


	public function actionSetProfileDetails2(){
	
                $serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
	
		$source_serial_number = Yii::app()->request->getParam('source_serial_number', '');
                $source_smartapp_id = Yii::app()->request->getParam('source_smartapp_id', '');
                $value_extra = json_decode(Yii::app()->request->getParam('value_extra', ''));
                $robot_profile = Yii::app()->request->getParam('profile', '');
                
                $utc_str = gmdate("M d Y H:i:s", time());
                $utc = strtotime($utc_str);
                $expected_time = 1;
                
                if(empty($source_serial_number) && empty($source_smartapp_id)){
                    self::terminate(-1, "Please provide atleast one source(source_serial_number or source_smartapp_id)");
                }
                
                if(!empty($source_smartapp_id)){
                   if (!AppHelper::is_valid_email($source_smartapp_id)) {
                        self::terminate(-1, 'Please enter valid email address in field source_smartapp_id.');
                   } 
                   
                   $user_data = User::model()->find('email = :email', array(':email' => $source_smartapp_id));
                   if(empty($user_data)){
                       self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) does not exist in our system.');
                   }
                   
                   $associated_user_check = false;
                   foreach ($user_data->usersRobots as $usersRobot) {
                        if($usersRobot->id_robot == $robot->id){ 
                            $associated_user_check = true;
                        }
                    }
                    if(!$associated_user_check){
                        self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) is not associated with given robot');
                    }
                    
                }
                    
                if($value_extra != null){
                   $value_extra = serialize($value_extra);
                }
                
		if ($robot !== null){
                    
                        $robot->value_extra = $value_extra;
                        $robot->save();
                        
			foreach ($robot_profile as $key => $value){
                                $key = trim($key);
				switch ($key) {
					case "name":
						$robot->name = $value;
						$robot->save();
						break;
					
					default:
                                            $data = RobotKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $key, ':robot_id' => $robot->id));
                                            if(!empty($data)){
//                                                if(empty($value)){
//                                                    RobotKeyValues::model()->deleteAll('id = :id', array(':id' => $data->id));
//                                                } else {
                                                    $data->value = $value;
                                                    $data->timestamp = $utc;
                                                    $data->update();
//                                                }
                                            } else {
//                                                if(empty($value)){
//                                                    continue;
//                                                }
                                                $robot_key_value = new RobotKeyValues();
                                                $robot_key_value->robot_id = $robot->id;
                                                $robot_key_value->_key = $key ;
                                                $robot_key_value->value = $value ;
                                                $robot_key_value->timestamp = $utc;
                                                $robot_key_value->save();                                                    
                                            }
                                        break;
				}
			}
                        
                        $xmpp_message_model = new XmppMessageLogs();
                        $xmpp_message_model->save();
                        $message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId></params></request></payload></packet>';
                        $xmpp_message_model->xmpp_message = $message;
                        $xmpp_message_model->save();
                        
                        $online_users_chat_ids = AppCore::getOnlineUsers();
                            
                        if(!empty($source_serial_number) && $source_serial_number == $serial_number){
                            foreach ($robot->usersRobots as $userRobot){

                                if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                        AppCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
                                }
                            }
                        } else if(!empty($source_smartapp_id)) {

                            AppCore::send_chat_message($user_data->chat_id, $robot->chat_id , $message);
                            if(!in_array($robot->chat_id, $online_users_chat_ids)){
                                
                                $robot_ping_data = AppCore::getLatestPingTimestampFromRobot($robot->id);
                                
//                                $robot_ping_data_set = isset($robot_ping_data[0]->ping_timestamp) ? $robot_ping_data[0]->ping_timestamp : '';
                                
                                $app_config = AppConfiguration::model()->find('_key = :_key', array(':_key' => 'ROBOT_PING_INTERVAL'));
                                $robot_ping_interval = $app_config->value;
                                $expected_time = $robot_ping_interval;
                                
                                if(isset($robot_ping_data[0]->ping_timestamp)){
                                
                                    $latest_ping_timestamp = strtotime($robot_ping_data[0]->ping_timestamp);
                                    $current_system_timestamp = time();
                                    $time_diff = ($current_system_timestamp - $latest_ping_timestamp);
                                    $expected_time = $robot_ping_interval - $time_diff;
                                    
                                }
                            }
                            foreach ($robot->usersRobots as $userRobot){
                                if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                    if($user_data->chat_id != $userRobot->idUser->chat_id){
                                        AppCore::send_chat_message($user_data->chat_id, $userRobot->idUser->chat_id, $message);
                                    }
                                }                                   
                            }
                            
                        }
                        
			self::successWithExtraParam(1, array('expected_time' => $expected_time));
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}        
        
	public function actionSetProfileDetails3(){
	
                $serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
	
		$source_serial_number = Yii::app()->request->getParam('source_serial_number', '');
                $source_smartapp_id = Yii::app()->request->getParam('source_smartapp_id', '');
                $cause_agent_id = Yii::app()->request->getParam('cause_agent_id', '');
                $value_extra = json_decode(Yii::app()->request->getParam('value_extra', ''));
                $notification_flag = $_REQUEST['notification_flag'];
                $robot_profile = Yii::app()->request->getParam('profile', '');
                
                $utc_str = gmdate("M d Y H:i:s", time());
                $utc = strtotime($utc_str);
                $expected_time = 1;
                
                if(empty($source_serial_number) && empty($source_smartapp_id)){
                    self::terminate(-1, "Please provide atleast one source(source_serial_number or source_smartapp_id)");
                }
                
                if(!empty($source_smartapp_id)){
                   if (!AppHelper::is_valid_email($source_smartapp_id)) {
                        self::terminate(-1, 'Please enter valid email address in field source_smartapp_id.');
                   } 
                   
                   $user_data = User::model()->find('email = :email', array(':email' => $source_smartapp_id));
                   if(empty($user_data)){
                       self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) does not exist in our system.');
                   }
                   
                   $associated_user_check = false;
                   foreach ($user_data->usersRobots as $usersRobot) {
                        if($usersRobot->id_robot == $robot->id){ 
                            $associated_user_check = true;
                        }
                    }
                    if(!$associated_user_check){
                        self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) is not associated with given robot');
                    }
                    
                }
                    
                if($value_extra != null){
                   $value_extra = serialize($value_extra);
                }
                
		if ($robot !== null){
                    
                        $robot->value_extra = $value_extra;
                        $robot->save();
                        
			foreach ($robot_profile as $key => $value){
                                $key = trim($key);
				switch ($key) {
					case "name":
						$robot->name = $value;
						$robot->save();
						break;
					
					default:
                                            $data = RobotKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $key, ':robot_id' => $robot->id));
                                            if(!empty($data)){
                                                $data->value = $value;
                                                $data->timestamp = $utc;
                                                $data->update();
                                            } else {
                                                $robot_key_value = new RobotKeyValues();
                                                $robot_key_value->robot_id = $robot->id;
                                                $robot_key_value->_key = $key ;
                                                $robot_key_value->value = $value ;
                                                $robot_key_value->timestamp = $utc;
                                                $robot_key_value->save();                                                    
                                            }
                                        break;
				}
			}
                        
                        $xmpp_message_model = new XmppMessageLogs();
                        $xmpp_message_model->save();
                        $message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><causeAgentId>' . $cause_agent_id . '</causeAgentId></params></request></payload></packet>';
                        $xmpp_message_model->xmpp_message = $message;
                        $xmpp_message_model->save();
                        
                        $online_users_chat_ids = AppCore::getOnlineUsers();
                            
                        if(!empty($source_serial_number) && $source_serial_number == $serial_number && $notification_flag){
                            
                            foreach ($robot->usersRobots as $userRobot){
                                if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                        AppCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
                                }
                            }
                            AppCore::send_chat_message($robot->chat_id, $robot->chat_id, $message);
                            
                        } else if(!empty($source_smartapp_id)) {


                            if(!in_array($robot->chat_id, $online_users_chat_ids)){
                                
                                $robot_ping_data = AppCore::getLatestPingTimestampFromRobot($robot->id);
                                
                                $app_config = AppConfiguration::model()->find('_key = :_key', array(':_key' => 'ROBOT_PING_INTERVAL'));
                                $robot_ping_interval = $app_config->value;
                                $expected_time = $robot_ping_interval;
                                
                                if(isset($robot_ping_data[0]->ping_timestamp)){
                                
                                    $latest_ping_timestamp = strtotime($robot_ping_data[0]->ping_timestamp);
                                    $current_system_timestamp = time();
                                    $time_diff = ($current_system_timestamp - $latest_ping_timestamp);
                                    $expected_time = $robot_ping_interval - $time_diff;
                                    
                                }
                            }
                            
                            if($notification_flag){
                                AppCore::send_chat_message($user_data->chat_id, $robot->chat_id , $message);
                                foreach ($robot->usersRobots as $userRobot){
                                    if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                        AppCore::send_chat_message($user_data->chat_id, $userRobot->idUser->chat_id, $message);
                                    }                                   
                                }
                            }
                            
                        }
                        
			self::successWithExtraParam(1, array('expected_time' => $expected_time, 'timestamp'=>$utc));
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}                
        
	public function actionGetProfileDetails(){
                    
                $serial_number = Yii::app()->request->getParam('serial_number', '');
                $key = Yii::app()->request->getParam('key', '');
                
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
		
		if ($robot !== null){
                        $data = RobotKeyValues::model()->findAll('robot_id= :robot_id', array(':robot_id' => $robot->id));
                        $profileArray = array();
                        $profileArray['name'] = $robot->name;
                        $profileArray['serial_number'] = $robot->serial_number;
                        if(!empty($data)){
                            
                            foreach ($data as $datarow){
                                if($key == $datarow->_key || empty ($key)){
                                    $profileArray[$datarow->_key] = $datarow->value;
                                }
                            }
                            if(count($profileArray) == 2){
                                self::terminate(-1, "Sorry, entered key is invalid");
                            }
                            
                        }
			$response_data = array("success"=>true, "profile_details" => $profileArray);
                        self::success($response_data);
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}        

	public function actionGetProfileDetails2(){
                    
                $serial_number = Yii::app()->request->getParam('serial_number', '');
                $key = Yii::app()->request->getParam('key', '');
                
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
		
		if ($robot !== null){
                        $data = RobotKeyValues::model()->findAll('robot_id= :robot_id', array(':robot_id' => $robot->id));
                        $profileArray = array();
                        $profileArray['name'] = array('value' => $robot->name, 'timestamp' => 0);
                        $profileArray['serial_number'] = array('value' => $robot->serial_number, 'timestamp' => 0);
                        if(!empty($data)){
                            
                            foreach ($data as $datarow){
                                if($key == $datarow->_key || empty ($key)){
                                    $profileArray[$datarow->_key] = array('value' => $datarow->value, 'timestamp' => $datarow->timestamp);
                                }
                            }
                            if(count($profileArray) == 2){
                                self::terminate(-1, "Sorry, entered key is invalid");
                            }
                            
                        }
			$response_data = array("success"=>true, "profile_details" => $profileArray);
                        self::success($response_data);
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}                
        
	public function actionDeleteRobotProfileKey(){
                    
                $serial_number = Yii::app()->request->getParam('serial_number', '');
                $key = Yii::app()->request->getParam('key', '');
                
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
		
		if ($robot !== null){

                        $result = RobotKeyValues::model()->deleteAll('robot_id = :robot_id AND _key = :_key', array(':robot_id' => $robot->id, ':_key' => $key));                        
                        
                        if($result) {
                            $response_data = array("success"=>true);
                            self::success($response_data);
                        } else {
                            self::terminate(-1, "Sorry, entered key is invalid");
                        }
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}        
        
	public function actionDeleteRobotProfileKey2(){
                    
                $serial_number = Yii::app()->request->getParam('serial_number', '');
                $key = Yii::app()->request->getParam('key', '');
                $cause_agent_id = Yii::app()->request->getParam('cause_agent_id', '');
                $source_serial_number = Yii::app()->request->getParam('source_serial_number', '');
                $source_smartapp_id = Yii::app()->request->getParam('source_smartapp_id', '');
                $notification_flag = $_REQUEST['notification_flag'];
                
                $utc_str = gmdate("M d Y H:i:s", time());
                $utc = strtotime($utc_str);
                
                $robot = self::verify_for_robot_serial_number_existence($serial_number);

                if(!empty($source_smartapp_id)){
                   if (!AppHelper::is_valid_email($source_smartapp_id)) {
                        self::terminate(-1, 'Please enter valid email address in field source_smartapp_id.');
                   } 
                   
                   $user_data = User::model()->find('email = :email', array(':email' => $source_smartapp_id));
                   if(empty($user_data)){
                       self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) does not exist in our system.');
                   }
                   
                   $associated_user_check = false;
                   foreach ($user_data->usersRobots as $usersRobot) {
                        if($usersRobot->id_robot == $robot->id){ 
                            $associated_user_check = true;
                        }
                    }
                    if(!$associated_user_check){
                        self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) is not associated with given robot');
                    }
                    
                }
                
		if ($robot !== null){

                        $result = RobotKeyValues::model()->deleteAll('robot_id = :robot_id AND _key = :_key', array(':robot_id' => $robot->id, ':_key' => $key));                        
                        
                        if($result) {
                            
                            $xmpp_message_model = new XmppMessageLogs();
                            $xmpp_message_model->save();
                            $message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><causeAgentId>' . $cause_agent_id . '</causeAgentId></params></request></payload></packet>';
                            $xmpp_message_model->xmpp_message = $message;
                            $xmpp_message_model->save();

                            $online_users_chat_ids = AppCore::getOnlineUsers();

                            if(!empty($source_serial_number) && $source_serial_number == $serial_number && $notification_flag){

                                foreach ($robot->usersRobots as $userRobot){
                                    if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                            AppCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
                                    }
                                }
                                AppCore::send_chat_message($robot->chat_id, $robot->chat_id, $message);

                            } else if(!empty($source_smartapp_id) && $notification_flag) {

                                AppCore::send_chat_message($user_data->chat_id, $robot->chat_id , $message);
                                foreach ($robot->usersRobots as $userRobot){
                                    if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                        AppCore::send_chat_message($user_data->chat_id, $userRobot->idUser->chat_id, $message);
                                    }                                   
                                }

                            } else if($notification_flag){
                                foreach ($robot->usersRobots as $userRobot){
                                    if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
                                            AppCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
                                    }
                                }
                                AppCore::send_chat_message($robot->chat_id, $robot->chat_id, $message);
                            }
                            
                            $response_data = array("success"=>true);
                            self::success($response_data);
                            
                        } else {
                            self::terminate(-1, "Sorry, entered key is invalid");
                        }
                        
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}        
        
	/**
	 * API to get array of associated users with provided robot serial no
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine and user association exist
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and user association does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":[]}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetAssociatedUser(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$users_arr = array();
		foreach ($robot->usersRobots as $user_robots){
			$user_details = array();
			$user_details['id'] = $user_robots->idUser->id;
			$user_details['name'] = $user_robots->idUser->name;
			$user_details['email'] = $user_robots->idUser->email;
			$user_details['chat_id'] = $user_robots->idUser->chat_id;

			$users_arr[] = $user_details;
		}
		self::success($users_arr);
	}

	/**
	 * API to set user and robot association
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User Email ID</li>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot
	 *				ownership established successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If ownership already exists.
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"This robot
	 *				ownership relation already exists."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Email is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter email in method
	 *				robot.set_user"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If Robot serial number is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robot.set_user"}</li>
	 *		</ul>
	 *</ul>
	 */
	public function actionSetUsers(){
		$user_email = Yii::app()->request->getParam('email', '');
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user = User::model()->findByAttributes(array('email' => $user_email));
		if($user !== null ){
			$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
			$user_robot = UsersRobot::model()->findByAttributes(array('id_user' => $user->id,'id_robot' => $robot->id));
			if($user_robot !== null){
				$response_data = array("success"=>true, "message"=>self::yii_api_echo('This robot ownership relation already exists.'));
				self::success($response_data);
			}
			else{
				$user_robots = new UsersRobot();
				$user_robots->id_user = $user->id;
				$user_robots->id_robot = $robot->id;
				if($user_robots->save()){
					$response_data = array("success"=>true, "message"=>self::yii_api_echo('Robot ownership established successfully.'));
					self::success($response_data);
				}
			}
		}
		else{
			$response_message = self::yii_api_echo('Email does not exist');
			self::terminate(-1, $response_message);
		}

	}

	/**
	 * API to disassociate users from robot
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *	<li><b>email</b> :User's Email (If this field is empty, it will
	 *		delete all user association for this particular robot)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine, user email provided and robot user
	 *		association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot User
	 *				association removed successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine, user email not provided and robot
	 *		user association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot
	 *				association with all user removed successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and robot user association does not
	 *		exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"There is no
	 *				association between provided robot and user"}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionDisAssociateUser(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		if($user_email){
			$user = User::model()->findByAttributes(array('email' => $user_email));
			if($user !== null){
				$user_robot_delete = UsersRobot::model()->deleteAllByAttributes(array('id_user' => $user->id,'id_robot' => $robot->id));
				if($user_robot_delete){
					$response_message = self::yii_api_echo('Robot User association removed successfully.');
				}else{
					$response_message = self::yii_api_echo('There is no association between provided robot and user');
				}
			}else {
				$response_message=self::yii_api_echo('Email does not exist.');
				self::terminate(-1, $response_message);
			}
		}else{
			$user_robots_delete = UsersRobot::model()->deleteAllByAttributes(array('id_robot' => $robot->id));
			if ($user_robots_delete){
				$response_message = self::yii_api_echo('Robot association with all user removed successfully.');
			}else{
				$response_message = self::yii_api_echo('There is no association between provided robot and user');
			}
		}
		$response_data = array("success"=>true, "message"=>$response_message);
		self::success($response_data);
	}

	/**
	 * Send start command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendStartCommand()
	{
		$chat_id = Yii::app()->request->getParam('chat_id', '');
		$to = AppHelper::two_way_string_decrypt($chat_id);

		$from = User::model()->findByPk(Yii::app()->user->id)->chat_id;

		$start_command = Yii::app()->params['robot-start-cleaning-command'];
		$message= AppCore::send_chat_message($from, $to, $start_command);
		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	/**
	 * Send stop command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendStopCommand()
	{
		$chat_id = Yii::app()->request->getParam('chat_id', '');
		$to = AppHelper::two_way_string_decrypt($chat_id);

		$from = User::model()->findByPk(Yii::app()->user->id)->chat_id;

		$stop_command = Yii::app()->params['robot-stop-cleaning-command'];
		$message= AppCore::send_chat_message($from, $to, $stop_command);
		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}
        
        
        public function actionRobotDataTable() {
                $userColumns = array('id', 'serial_number');
                $userIndexColumn = "id";
                $userTable = "robots";
                $userDataModelName = 'Robot';
                $result = AppCore::dataTableOperation($userColumns, $userIndexColumn, $userTable, $_GET, $userDataModelName);
                
                /*
                 * Output
                 */
                $output = array(
                    'sEcho' => $result['sEcho'],
                    'iTotalRecords' => $result['iTotalRecords'],
                    'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
                    'aaData' => array()
                );
                
                foreach ($result['rResult'] as $robot) {

                    $row = array();
                    
                    $schedule = '';
                    $atlas = '';
                    $grid = '';
                    $map = '';
                    
                    $select_checkbox = '<input type="checkbox" name="chooseoption[]" value="'.$robot->id.'" class="choose-option">';
                    $serial_number = '<a rel="'.$this->createUrl('/robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($robot->id))).'" href="'.$this->createUrl('/robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id))).'" class="qtiplink robot-qtip" title="View details of ('.$robot->serial_number.')">'.$robot->serial_number.'</a>';
                    $robot_type = isset($robot->robotRobotTypes->robotType) ? $robot->robotRobotTypes->robotType->name . ' (' . $robot->robotRobotTypes->robotType->type . ')' : '';
                    
                    $associated_users = '';
                    if ($robot->doesUserAssociationExist()) {
                        $is_first_user = true;
                        foreach ($robot->usersRobots as $value) {
                            if (!$is_first_user) {
                                $associated_users .= ",";
                            }
                            $is_first_user = false;
                            $associated_users .= "<a class='single-item qtiplink' title='View details of (" . $value->idUser->email . ")' rel='" . $this->createUrl('/user/userprofilepopup', array('h' => AppHelper::two_way_string_encrypt($value->idUser->id))) . "' href='" . $this->createUrl('/user/userprofile', array('h' => AppHelper::two_way_string_encrypt($value->idUser->id))) . "'>" . $value->idUser->email . "</a>";
                        }
                    }
                    
                    if ($robot->doesMapExist()) {
                        $map = '<a href="'.$this->createUrl('/robot/view', array('h' => AppHelper::two_way_string_encrypt($robot->id), 'scroll_to' => 'map_section')) .'" title="View map details of robot ('.$robot->serial_number.')"> Yes </a>';
                    }	
                    
                    if ($robot->doesScheduleExist()) {
			$schedule ='<a href="'.$this->createUrl('/robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'schedule_section')).'" title="View schedule details of robot ('.$robot->serial_number.')"> Yes </a>';					
                    }	

                    if ($robot->doesAtlasExist()){
                        $atlas ='<a href='.$this->createUrl('/robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'atlas_section')).' title="View atlas details of robot ('.$robot->serial_number.')"> Yes </a>';
                    }	
                    
                    if ($robot->doesAtlasExist() && $robot->robotAtlas->doesGridImageExist()){
			$grid =  '<a href="'.$this->createUrl('/robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id), 'scroll_to'=>'atlas_section')).'" title="View atlas details of robot ('.$robot->serial_number.')"> Yes </a>';
                    }	
                    
                    $edit = '<a href="'.$this->createUrl('/robot/update',array('h'=>AppHelper::two_way_string_encrypt($robot->id))).'" title="Edit robot '.$robot->serial_number.'">edit</a>';
                    
                    $row[] = $select_checkbox;
                    $row[] = $serial_number;
                    $row[] = $robot_type;
                    $row[] = $associated_users;
                    $row[] = $schedule;
                    $row[] = $atlas;
                    $row[] = $grid;
                    $row[] = $map;
                    $row[] = $edit;
                    
                    $output['aaData'][] = $row;
                }

                $this->renderPartial('/default/defaultView', array('content' => $output));
    }
    
    public function actionPingFromRobot() {
        
        $serial_number = Yii::app()->request->getParam('serial_number', '');
        $status = Yii::app()->request->getParam('status', '');
        
        $robot = self::verify_for_robot_serial_number_existence($serial_number);
		
        if($robot !== null ){
            
            $message = 'robot ping have been recorded';
 
            $robot_ping_log = new RobotPingLog();
            $robot_ping_log->robot_id = $robot->id;
            $robot_ping_log->ping_timestamp = new CDbExpression('NOW()');
            $robot_ping_log->status = $status;
            $robot_ping_log->save();
            
            $response_data = array("success"=>true, "message"=>$message);
            self::success($response_data);
            
        }
        
    }
    
    public function actionGetRobotTypeMetadataUsingType() {
        
        $robot_type = Yii::app()->request->getParam('robot_type', '');
        
        $robot_type_data = RobotTypes::model()->find('type = :type', array(':type' => $robot_type));
        
        if(!empty($robot_type_data)){
            
            $metadata = array();
            foreach ($robot_type_data->robotTypeMetadatas as $value) {
                $metadata[$value->_key] = $value->value;
            }
            
            $response_data = array("success"=>true, "robot_metadata"=>array('type' => $robot_type_data->type, 'metadata' => $metadata ));
            self::success($response_data);
            
        } else {
            self::terminate(-1, "Provided robot type is not valid");
        }
        
    }
    
    public function actionGetRobotTypeMetadataUsingId() {
        
        $serial_number = Yii::app()->request->getParam('serial_number', '');
        
        $robot = self::verify_for_robot_serial_number_existence($serial_number);
        
        $robot_robot_type = $robot->robotRobotTypes;
        
//        if(empty($robot_robot_type)) {
//            $robot_robot_type = new RobotRobotTypes();
//            
//            $robot_robot_type->robot_id = $robot->id;
//            $robot_robot_type->robot_type_id = 1;
//            
//            $robot_robot_type->save();
//            
//        }

        $robot_type_data = RobotTypes::model()->findByPk($robot_robot_type->robot_type_id);
        
        if(!empty($robot_type_data)){
            
            $metadata = array();
            foreach ($robot_type_data->robotTypeMetadatas as $value) {
                $metadata[$value->_key] = $value->value;
            }
            
            $response_data = array("success"=>true, "robot_metadata"=>array('type' => $robot_type_data->type, 'metadata' => $metadata ));
            self::success($response_data);
            
        } else {
            self::terminate(-1, "Associated robot type does not exist");
        }

    }
    
}