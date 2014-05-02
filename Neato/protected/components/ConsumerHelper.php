<?php

/**
 * Common helper functions used by consumer.
 */
class ConsumerHelper {

	public static function sendGCMPushNotification($registration_ids_gcm, $message_body){
		// API key from Google APIs
		$apiKey = Yii::app()->params['gcm_api_key'];
		// Set POST variables
		$url_gcm = 'https://android.googleapis.com/gcm/send';
		$headers_gcm = array(
				'Authorization: key=' . $apiKey,
				'Content-Type: application/json'
		);
		$data_string_gcm = json_encode(array(
				'registration_ids' => $registration_ids_gcm,
				'data' => $message_body
		));
		$gcm_result = AppHelper::curl_call($url_gcm, $headers_gcm, $data_string_gcm);
		$result_object = json_decode($gcm_result);
		$removableStatuses = array();
		$removableStatuses[] = 'NotRegistered';
		$removableStatuses[] = 'InvalidRegistration';
		$removableStatuses[] = 'MismatchSenderId';
		if ($result_object->failure > 0 || $result_object->canonical_ids > 0) {
			foreach ($registration_ids_gcm as $key => $value) {
				$returnedErrorCode = isset($result_object->results[$key]->error) ? $result_object->results[$key]->error : '';
				if (in_array($returnedErrorCode, $removableStatuses)) {
					NotificationRegistrations::model()->updateAll(array('is_active'=>'N'), 'registration_id=:registration_id', array(':registration_id'=>$value));
				} else {
					$new_registration_id = isset($result_object->results[$key]->registration_id) ? $result_object->results[$key]->registration_id : false;
					if ($new_registration_id) {
						$row = NotificationRegistrations::model()->find('registration_id=:registration_id', array(':registration_id'=>$new_registration_id));
						if (empty($row)) {
							NotificationRegistrations::model()->updateAll(array('registration_id'=>$new_registration_id, 'is_active'=>'Y'), 'registration_id=:registration_id', array(':registration_id'=>$value));
						} else {
							NotificationRegistrations::model()->updateAll(array('is_active'=>'N'), 'registration_id=:registration_id', array(':registration_id'=>$value));
						}
						$notification_registration_id_logs = new NotificationRegistrationIdLogs();
						$notification_registration_id_logs->old_registration_id = $value;
						$notification_registration_id_logs->new_registration_id = $new_registration_id;
						if(!$notification_registration_id_logs->save()){
							error_log("+++++++++++++++++++++++++++", 0);
							var_dump($notification_registration_id_logs->errors);
							error_log("+++++++++++++++++++++++++++", 0);
						}
					}
				}
			}
		}
		return $gcm_result;
	}

	public static function sendIOSPushNotification($deviceToken, $message_body, $registration_type_ios, $application_id_ios) {

		$loc_key = array();
		$loc_key[101] = 'MSG_STUCK_NOTIFICATION_ID';
		$loc_key[102] = 'MSG_DIRT_BAG_FULL_NOTIFICATION_ID';
		$loc_key[103] = 'MSG_CLEANING_DONE_NOTIFICATION_ID';
		$loc_key[212] = 'MSG_UI_ALERT_PLUG_CABLE_NOTIFICATION_ID';
		$loc_key[22000] = 'MSG_UI_ERR_CANCEL_NOTIFICATION_ID';
		$loc_key[20219] = 'MSG_UI_ERR_DUST_BIN_MISSING_NOTIFICATION_ID';

		$loc_key_value = isset($message_body['notificationId']) ? $loc_key[$message_body['notificationId']] : 'MSG_GENERIC_NOTIFICATION_ID';

		// Create the payload body
		$body['aps'] = array(
				'alert' => array(
						'loc-key' => $loc_key_value,
						'action-loc-key' => "VIEW"));

		$message_body_ios = array();
		if(isset($message_body['robotId'])){
			$message_body_ios['robotId'] = $message_body['robotId'];
		}
		if (isset($message_body['notificationId'])) {
			$message_body_ios['id'] = $message_body['notificationId'];
		}
		$message_body_ios['message'] = $message_body['message'];
		$message_body_ios['time'] = $message_body['time'];

		$body['raw_data'] = $message_body_ios;

		// Encode the payload as JSON
		$payload = json_encode($body);

		$result = '';

		$app_type_index = 0;

		foreach ($deviceToken as $reg_id) {

			$fp = Null;
			if(!empty($registration_type_ios) && strtoupper($registration_type_ios[$app_type_index]) == 'DIST'){
				$fp = self::fp_for_dist($application_id_ios[$app_type_index]);
			}else {
				$fp = self::fp_for_dev($application_id_ios[$app_type_index]);
			}

			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $reg_id) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result .= fwrite($fp, $msg, strlen($msg)) . ' ';

			$app_type_index++;
		}

		// Close the connection to the server
		fclose($fp);

		return $result;
	}

	private function fp_for_dev($application_id_ios){

		$iOSCertificatesPath = Yii::app()->basePath. '/certificates/Dev/neato.pem';
		$passphrase = 'neato123';

		error_log("++++++++++++++++++++++++++++++++++++++++", 0);
		error_log("DEV", 0);
		error_log($application_id_ios, 0);
		error_log("++++++++++++++++++++++++++++++++++++++++", 0);

		if(!empty($application_id_ios) && $application_id_ios == 'com.vorwerk-robot.vr200beta'){
			$iOSCertificatesPath = Yii::app()->basePath. '/certificates/Dev/vorwerk_push_notification_server_dev.pem';
			$passphrase = 'vorwerk123';
			error_log("===========================================", 0);
			error_log($iOSCertificatesPath, 0);
			error_log("===========================================", 0);
		}

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $iOSCertificatesPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp_dev = stream_socket_client(
				'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp_dev) {
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		}

		return $fp_dev;
	}

	private function fp_for_dist($application_id_ios){

		$iOSCertificatesPath = Yii::app()->basePath. '/certificates/Dist/neato.pem';
		$passphrase = 'neato123';

		error_log("++++++++++++++++++++++++++++++++++++++++", 0);
		error_log("DIST", 0);
		error_log($application_id_ios, 0);
		error_log("++++++++++++++++++++++++++++++++++++++++", 0);

		if(!empty($application_id_ios) && $application_id_ios == 'com.vorwerk-robot.vr200beta'){
			$iOSCertificatesPath = Yii::app()->basePath. '/certificates/Dist/vorwerk_push_notification_server_dist.pem';
			$passphrase = 'vorwerk123';
			error_log("===========================================", 0);
			error_log($iOSCertificatesPath, 0);
			error_log("===========================================", 0);
		}

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $iOSCertificatesPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp_dist = stream_socket_client(
				'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp_dist) {
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		}
		return $fp_dist;

	}

}