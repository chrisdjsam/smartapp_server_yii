<?php

class UserCore {

	/**
	 *
	 * Create chat id and chat password for user
	 * @return array
	 */
	public static function create_chat_user_for_user() {

		$chat_details = array();
		$ts = time();

		$ejabberd_node = Yii::app()->params['ejabberdhost'];
		$chat_user = $ts . "_user";

		$chat_id = $chat_user . '@' . $ejabberd_node;
		$chat_pwd = $ts . "_user";

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
		return $chat_details;
	}

	/**
	 * Create Auth Token for User
	 * @param int $user_id
	 * @return boolean
	 */
	public static function create_user_auth_token($user_id) {
		$user_auth_token = sha1(microtime() . getmypid() . ApiUser::model()->count());
		$ts = time();
		$user_auth_tken_valid_till = Yii::app()->params['user-auth-token-valid-till'];
		$duration = $ts + 3600 * 24 * $user_auth_tken_valid_till;

		$api_key = $_REQUEST['api_key'];
		$site_id = self::get_site_id();
		$api_user_model = ApiUser::model()->findByAttributes(array('api_key' => $api_key));
		if (!is_null($api_user_model)) {
			$site_id = $api_user_model->id_site;
		}

		$user_api_sessions = new UsersApiSession();
		$user_api_sessions->id_user = $user_id;
		$user_api_sessions->id_site = $site_id;
		$user_api_sessions->token = $user_auth_token;
		$user_api_sessions->expires = $duration;
		if ($user_api_sessions->save()) {
			return $user_auth_token;
		}

		return false;
	}

	/**
	 * Get site id (It is used by API calls)
	 * @return int
	 */
	public static function get_site_id() {
		return 1;
	}

	public static function getGracePeriod() {

		$grace_period = '';
		$grace_period = AppConfiguration::model()->findByAttributes(array('_key' => 'GRACE_PERIOD'));
		$grace_period = isset($grace_period->value) ? $grace_period->value : 60;

		return $grace_period;
	}

	public static function getIsValidateStatus($is_validated, $user_id) {

		$user_data = User::model()->findByPk($user_id);
		$created_on = $user_data->created_on;

		$is_validated = ($is_validated == 0) ? -1 : 0;

		if ($is_validated == -1) {
			$grace_period = self::getGracePeriod();
			$user_created_on_timestamp = strtotime($created_on);
			$current_system_timestamp = time();

			$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

			if ($time_diff > $grace_period) {
				$is_validated = -2;
			}
		}

		return $is_validated;
	}

	public static function setDefaultUserPushNotificationOptions($user_id) {

		$json_object = json_decode(Yii::app()->params['default_json_for_notification_preference']);

		$userPushNotificationPreferencesObj = UserPushNotificationPreferences::model()->findAll('user_id = :user_id', array(':user_id' => $user_id));

		if(empty($userPushNotificationPreferencesObj)){

			foreach ($json_object->notifications as $value) {

				$userPushNotificationPreferencesObj = new UserPushNotificationPreferences();
				self::setUserPushNotificationOptions($userPushNotificationPreferencesObj, $user_id, $value->key, $value->value);

			}

		} else {

			foreach ($json_object->notifications as $key => $value) {

				self::setUserPushNotificationOptions($userPushNotificationPreferencesObj[$key], $user_id, $value->key, $value->value);

			}

		}

	}

	public static function setUserPushNotificationOptions($userPushNotificationPreferencesObj, $user_id, $push_notification_types_id, $preference) {

		$userPushNotificationPreferencesObj->user_id = $user_id;
		$userPushNotificationPreferencesObj->push_notification_types_id = $push_notification_types_id;
		$userPushNotificationPreferencesObj->preference = (int)filter_var($preference, FILTER_VALIDATE_BOOLEAN);

		if(!$userPushNotificationPreferencesObj->save()){
			//do nothing
		}

	}

	public static function getValidationAttempt() {

		$validation_attempt_data = AppConfiguration::model()->findByAttributes(array('_key' => 'VALIDATION_ATTEMPT'));
		$validation_attempt = isset($validation_attempt_data->value) ? $validation_attempt_data->value : 5;

		return $validation_attempt;
	}

}

?>
