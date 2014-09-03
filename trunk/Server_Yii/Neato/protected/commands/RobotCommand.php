<?php

class RobotCommand extends CConsoleCommand {

	public function actionJobToCreateRobots($prefix, $start, $end, $email=''){
		set_time_limit(0);

		if(empty($prefix) || empty($start) || empty($end)){
			echo "\nParameters 'prefix', 'start' and 'end' are mandatory. \n\n";
			Yii::app()->end();
		}else if(!ctype_digit(strval($start)) || !ctype_digit(strval($end))) {
			echo "\nParameters 'start' and 'end' must be valid number. \n\n";
			Yii::app()->end();
		}else if ($start >= $end ) {
			echo "\nParameters 'end' must be greater than 'start'. \n\n";
			Yii::app()->end();
		}
		if(!empty($email)){
			$user_model = User::model()->findByAttributes(array("email"=>$email));
			if($user_model == null ){
				echo "\nProvided 'email' does exist in the system. \n\n";
				Yii::app()->end();
			}
		}

		$serial_number_temp = $prefix;
		for($i = $start; $i <= $end; $i++){

			$serial_number = $serial_number_temp . $i;
			$robot = Robot::model()->findByAttributes(array('serial_number' => $serial_number));
			if($robot != null ){
				if(isset($user_model->id)){
					self::associateRobotToUser($user_model->id, $robot->id);
				}
				continue;
			}

			$model = new Robot();
			$model->name = "Tsung";
			$model->serial_number = $serial_number;

			$chat_details = array();
			$ts = $serial_number;
			$ejabberd_node = Yii::app()->params['ejabberdhost'];
			$chat_user = "robot_" . $ts;
			$chat_id = $chat_user . '@' . $ejabberd_node;
			$chat_pwd = "robot_" . $ts;
			$chat_details['jabber_status'] = true;
			$chat_details['chat_id'] = $chat_id;
			$chat_details['chat_pwd'] = $chat_pwd;

			if (Yii::app()->params['isjabbersetup']) {
				$jabberRegisterString = Yii::app()->params['ejabberdctl'] . ' register ' . $chat_user . ' ' . $ejabberd_node . ' ' . $chat_pwd . ' 2>&1';
				exec($jabberRegisterString, $output, $status);

				$success_string = strtolower("successfully registered");
				$message_string = isset($output[0]) ? $output[0] : '';
				$message_string = strtolower($message_string);

				preg_match("/$success_string/i", $message_string, $matches);

				if ($status != 0 || empty($matches)) {
					$chat_details['jabber_status'] = false;
				}
			}

			$model->chat_id = $chat_details['chat_id'];
			$model->chat_pwd = $chat_details['chat_pwd'];

			if ($model->save()) {
				$robot_robot_type = new RobotRobotTypes();
				$robot_robot_type->robot_id = $model->id;
				$robot_robot_type->robot_type_id = 1;
				$robot_robot_type->save();
				if(isset($user_model->id)){
					self::associateRobotToUser($user_model->id, $model->id);
				}
			}

		}
		echo "\nDone \n\n";
		Yii::app()->end();
	}

	function associateRobotToUser($user_id, $robot_id){
		$robot_user_association = UsersRobot::model()->find('id_user = :id_user and id_robot = :id_robot', array(':id_user' => $user_id, ':id_robot' => $robot_id));
		if($robot_user_association != null ){
			return true;
		}
		$user_robot_obj = new UsersRobot();
		$user_robot_obj->id_user = $user_id;
		$user_robot_obj->id_robot = $robot_id;
		$user_robot_obj->save();
		return true;
	}

}


?>