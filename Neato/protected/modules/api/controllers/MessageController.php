<?php

/**
 * The API MessageController is meant for all message related API actions.
 */
class MessageController extends APIController {
	/**
	 * This method is used to send XMPP message to robot.
	 * Parameters:
	* <ul>
	* 	<li><b>api_key</b> 		:Your API Key</li>
	* 	<li><b>user_id</b> 		:ID of user sending message.</li>
	* 	<li><b>serial_number</b>:serial_number of robot to whom user want to send message.</li>
	* 	<li><b>message</b> 		:Message Text</li>
	* 	</li>
	* </ul>
	* 
	* 
	* Success Response:
	* <ul>
	* 	<li>If message is sent to robot
	* 		<ul>
	* 			<li>
	* 				{"status":0,"result":{"success":true,"message":"Message is sent to robot 1."}}
	* 			</li>
	* 		</ul>
	* 	</li>
	* </ul>
	* 
	* Failure Responses: <br />
	* <ul>
	* 	<li>If API Key is missing:
	* 		<ul>
	* 			<li>{"status":-1,"message":"Method call failed the API
	* 				Authentication"}</li>
	* 		</ul>
	* 	</li>
	* 	
	* 	<li>If serial_nubmer not found:
	* 		<ul>
	* 			<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
	* 		</ul>
	* 	</li>
	* 	
	* 	<li>If user_id not found:
	* 		<ul>
	* 			<li>{"status":-1,"message":"User ID does not exist"}</li>
	* 		</ul>
	* 	</li>
	* 	
	* 	<li>If user and robot association not exist:
	*		<ul>
	*			<li>{"status":-1,"message":"User robot association does not exist."}</li>
	*		</ul>
	*	</li>
	* 	<li>If message sending failed:
	* 		<ul>
	* 			<li>{"status":-1,"message":"Message could not be sent to robot sr1."}</li>
	* 		</ul>
	* 	</li>
	* </ul>
	 */
	public function actionSendXmppMessageToRobot(){
		$id_user = Yii::app()->request->getParam('user_id', '');
		$user = self::verify_for_user_id_existence($id_user);
		
		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		if(!self::check_for_user_robot_association($id_user, $serial_number)){
			$response_message = "User robot association does not exist.";
			self::terminate(-1, $response_message);
		}
		
		$message = Yii::app()->request->getParam('message', '');
		
		$status = AppCore::send_chat_message($user->chat_id, $robot->chat_id, $message);
		if($status){
			$response_message = "Message is sent to robot $serial_number.";
			$response_data = array("success"=>true, "message"=>$response_message);
			self::success($response_data);
		}else{
			$response_message = "Message could not be sent to robot $serial_number.";
			self::terminate(-1, $response_message);
		}
	
	}
	
	/**
	 * Check is user robot association exist or not.
	 * @param int $user_id
	 * @param string $serial_number
	 * @return boolean
	 */
	protected function check_for_user_robot_association($user_id, $serial_number){
		$user = User::model()->findByPk($user_id);
		
		foreach($user->usersRobots as $usersRobot){
			if($serial_number == $usersRobot->idRobot->serial_number){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * This method would send a message to all associated users of robot.
	 *
	 * * 	Parameters:
	 * 	<ul>
	 * 		<li><b>api_key</b> 		:Your API Key</li>
	 * 		<li><b>serial_number</b>:Serial number of robot sending robot.</li>
	 * 		<li><b>message_type</b> :Type of message (Only XMPP is supported for now).</li>
	 * 		<li><b>message</b> 		:Message Text.</li>
	 *
	 * 	</ul>
	 *
	 *
	 * 	Success Response:
	 * 	<ul>
	 * 		<li>If message is sent to users
	 * 			<ul>
	 * 				<li>
	 * 					{"status":0,"result":{"success":true,"message":"Message is sent to 3 user(s)."}}
	 * 				</li>
	 * 			</ul>
	 * 		</li>
	 * 	</ul>
	 *
	 * 	Failure Responses: <br />
	 * 	<ul>
	 * 		<li>If API Key is missing:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Method call failed the API
	 * 					Authentication"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 		<li>If sending failed:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Message is sent to 0 user(s)."}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 		<li>If serial_nubmer not found:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 		<li>If message_type does not match:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"XMP does not match supported message type XMPP"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 	</ul>
	 *
	 */
	public function actionSendMessageToAssociatedUsers(){
	
		$message_type = Yii::app()->request->getParam('message_type', '');
	
		if(!in_array($message_type, array('XMPP','xmpp','Xmpp'))){
			$response_message = "$message_type does not match supported message type XMPP";
			self::terminate(-1, $response_message);
		}
	
		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
	
		$message = Yii::app()->request->getParam('message', '');
		$count= 0;
		foreach ($robot->usersRobots as $userRobot){
			$count += AppCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
		}
	
		$response_message = "Message is sent to $count user(s).";
		if($count){
			$response_data = array("success"=>true, "message"=>$response_message);
			self::success($response_data);
		}else{
			self::terminate(-1, $response_message);
		}
	}
	
}